<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Faq;
use App\Daran\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
// use Spatie\Tags\Tag;
use DOMDocument;
use App\Daran\Http\Requests\PageRequest;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can('read faq')){
            abort(503);
        }

        return view('daran::faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!Auth::user()->can('create faq')){
            abort(503);
        }
        $faq = new Faq();
        $faq->search = 0;
        $faq->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $faqCategories = FaqCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::faqs.create', compact('faq', 'faqCategories', 'locales', 'locale_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request)
    {
        if(!Auth::user()->can('create faq')) {
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = Faq::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0) {
                return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
            }
        }

        $faq = new Faq($request->all());
        $faq->user_id = Auth::user()->id;
        $faq->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $faq->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        if ($faq->state == 'public') {
            $faq->published_at = new Carbon();
        }

        if($request->filled('content')) {
            $faq->content = $this->saveContentImage($request->get('content'));
        }

        if ($faq->save()) {
            return Redirect::route('faqs.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        if(!Auth::user()->can('edit faq')){
            abort(503);
        }

        $faqCategories = FaqCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        return view('daran::faqs.edit', compact('faq', 'faqCategories', 'locales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        if(!Auth::user()->can('edit faq')){
            abort(503);
        }

        $num = Faq::where('locale','=',$request->locale)->where('locale_group','=',$faq->locale_group)->where('id','<>',$faq->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
        }

        if ($faq->state == 'draft' && $request->state == 'public') {
            $faq->published_at = new Carbon();
        }

        $faq->update($request->except('slug'));
        $faq->user_id = Auth::user()->id;
        $faq->slug = Str::slug($request->slug);
        if($request->filled('content')){
            $faq->content = $this->saveContentImage($request->get('content'));
        }

        if (!$request->has('seo')){
            $faq->seo = 0;
        }

        if (!$request->has('search')){
            $faq->search = 0;
        }

        if ($faq->save()) {
            return Redirect::route('faqs.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    /**
     * Clone the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function clone(Request $request, int $id)
     {
         if(!Auth::user()->can('create faq')){
             abort(503);
         }

         $faq = Faq::findOrFail($id);
         $clone = $faq->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('faqs.edit', ['faq' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('faqs.index')->with('error', trans('daran::message.error.clone'));
         }
     }
}
