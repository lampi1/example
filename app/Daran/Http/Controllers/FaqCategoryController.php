<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FaqCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read faq')){
            abort(503);
        }

        return view('daran::faq-categories.index');
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

        $faqCategory = new FaqCategory();
        $faqCategory->search = 0;
        $faqCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::faq-categories.create', compact('faqCategory', 'locales', 'locale_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('create faq')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $faqCategory = new FaqCategory();
        $faqCategory->name = $request->name;
        $faqCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $faqCategory->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($faqCategory->save()){
            return Redirect::route('faq-categories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(FaqCategory $faqCategory)
    {
        if(!Auth::user()->can('edit faq')){
            abort(503);
        }

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }
        $locale_group = $faqCategory->locale_group;

        return view('daran::faq-categories.edit', compact('faqCategory', 'locales' ,'locale_group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FaqCategory  $faqCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FaqCategory $faqCategory)
    {
        if(!Auth::user()->can('edit faq')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'slug' => 'required'
        ]);
        $request['slug'] = Str::slug($request->slug);
        if ($faqCategory->update($request->all())) {
            return Redirect::route('faq-categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }
}
