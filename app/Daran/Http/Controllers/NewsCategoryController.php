<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read news')){
            abort(503);
        }

        return view('daran::news-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }
        $category = new NewsCategory();
        $category->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::news-categories.create',compact('category', 'locales', 'locale_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $newsCategory = new NewsCategory($request->all());
        $newsCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $newsCategory->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($newsCategory->save()){
            return Redirect::route('news-categories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(NewsCategory $category)
    {
        if(!Auth::user()->can('edit news')){
            abort(503);
        }

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }
        $locale_group = $category->locale_group;

        return view('daran::news-categories.edit' , compact('category', 'locales','locale_group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewsCategory $newsCategory)
    {
        if(!Auth::user()->can('edit news')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'slug' => 'required'
        ]);
        $request['slug'] = Str::slug($request->slug);
        if ($newsCategory->update($request->all())) {
            return Redirect::route('admin.news-categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        $item = NewsCategory::findOrFail($id);
        $clone = $item->duplicate();
        $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        if ($clone->save()) {
            return Redirect::route('admin.news-categories.edit', ['category' => $clone])->with('success', trans('daran::common.duplication_success'));
        } else {
            return Redirect::route('admin.news-categories.index')->with('error', trans('daran::common.duplication_fail'));
        }
    }
}
