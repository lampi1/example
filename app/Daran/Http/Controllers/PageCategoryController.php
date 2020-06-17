<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\PageCategory;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can('read page')){
            abort(503);
        }

        return view('daran::page-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!Auth::user()->can('create page')){
            abort(503);
        }
        $pageCategory = new PageCategory();
        $pageCategory->search = 0;
        $pageCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::page-categories.create', compact('pageCategory', 'locales', 'locale_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('create page')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $pageCategory = new PageCategory();
        $pageCategory->name = $request->name;
        $pageCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $pageCategory->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($pageCategory->save()){
            return Redirect::route('admin.page-categories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PageCategory  $pagecategory
     * @return \Illuminate\Http\Response
     */
    public function edit(PageCategory $pageCategory)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $pageCategory->locale_group;

        return view('daran::page-categories.edit' , compact('pageCategory', 'locales', 'locale_group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PageCategory  $pagecategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PageCategory $pageCategory)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'slug' => 'required'
        ]);
        $request['slug'] = Str::slug($request->slug);
        if ($pageCategory->update($request->all())) {
            return Redirect::route('admin.page-categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

}
