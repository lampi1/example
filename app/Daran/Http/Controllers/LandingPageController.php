<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\LandingPage;
use App\Daran\Models\LandingPageTemplate;
use App\Daran\Models\Slider;
use App\Daran\Models\Form;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Daran\Http\Requests\LandingPageRequest;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read landing_page')){
            abort(503);
        }

        return view('daran::landing-pages.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create landing_page')){
            abort(503);
        }

        $landing_page = new LandingPage();
        $landing_page->search = 1;
        $landing_page->seo = 1;
        $landing_page->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $sliders = Slider::orderBy('name', 'ASC')->get();
        $forms = Form::orderBy('name', 'ASC')->get();
        $pageTemplates = LandingPageTemplate::orderBy('name','asc')->get();

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::landing-pages.create', compact('landing_page', 'sliders', 'pageTemplates', 'forms', 'locales', 'locale_group'));
    }

    public function store(LandingPageRequest $request)
    {
        if(!Auth::user()->can('create landing_page')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = LandingPage::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0){
                return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
            }
        }

        $landing_page = new LandingPage($request->except('image','image_sm','content'));
        $landing_page->user_id = Auth::user()->id;
        $landing_page->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $landing_page->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($landing_page->state == 'published') {
            $landing_page->published_at = new Carbon();
        }

        if($request->filled('content')){
            $landing_page->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $landing_page->locale.'-'.Str::slug($landing_page->title).'_'.uniqid().'.'.$extension;
            $landing_page->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $landing_page->locale.'-mobile-'.Str::slug($landing_page->title).'_'.uniqid().'.'.$extension;
            $landing_page->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $landing_page->locale.'-mobile-'.Str::slug($landing_page->title).'_'.uniqid().'.'.$extension;
            $landing_page->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if ($landing_page->save()) {
            return Redirect::route('admin.landing-pages.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Request $request, LandingPage $landing_page)
    {
        if(!Auth::user()->can('edit landing_page')){
            abort(503);
        }

        $sliders = Slider::orderBy('name', 'ASC')->get();
        $forms = Form::orderBy('name', 'ASC')->get();
        $pageTemplates = LandingPageTemplate::orderBy('name','asc')->get();

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        return view('daran::landing-pages.edit', compact('landing_page', 'forms', 'sliders', 'pageTemplates', 'locales'));
    }

    public function update(LandingPageRequest $request, LandingPage $landing_page)
    {
        if(!Auth::user()->can('edit landing_page')){
            abort(503);
        }

        $num = LandingPage::where('locale','=',$request->locale)->where('locale_group','=',$landing_page->locale_group)->where('id','<>',$landing_page->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
        }

        if ($landing_page->state == 'draft' && $request->state == 'published') {
            $landing_page->published_at = new Carbon();
        }

        $landing_page->update($request->except('image','image_sm','slug','content'));
        $landing_page->user_id = Auth::user()->id;
        $landing_page->slug = Str::slug($request->slug);

        if($request->filled('content')){
            $landing_page->content = $this->saveContentImage($request->get('content'));
        }

        $filename = array();
        if ($request->hasFile('image')) {
            $filename[] = $landing_page->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $landing_page->locale.'-'.Str::slug($landing_page->title).'_'.uniqid().'.'.$extension;
            $landing_page->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $landing_page->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $landing_page->locale.'-mobile-'.Str::slug($landing_page->title).'_'.uniqid().'.'.$extension;
            $landing_page->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if (!$request->has('seo')){
            $landing_page->seo = 0;
        }

        if (!$request->has('search')){
            $landing_page->search = 0;
        }

        if ($landing_page->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.landing-pages.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

     public function clone(Request $request, int $id)
     {
         if(!Auth::user()->can('create landing_page')){
             abort(503);
         }

         $landing_page = LandingPage::findOrFail($id);
         $clone = $landing_page->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('admin.landing-pages.edit', ['id' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.landing-pages.index')->with('error', trans('daran::message.error.clone'));
         }
     }
}
