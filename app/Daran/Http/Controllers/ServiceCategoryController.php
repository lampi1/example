<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read news')){
            abort(503);
        }

        return view('daran::service-categories.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }
        $category = new ServiceCategory();
        $category->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::service-categories.create',compact('category', 'locales', 'locale_group'));
    }

    public function store(Request $request)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $serviceCategory = new ServiceCategory($request->all());
        $serviceCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $serviceCategory->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($serviceCategory->save()){
            return Redirect::route('admin.service-categories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(ServiceCategory $category)
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

        return view('daran::service-categories.edit' , compact('category', 'locales','locale_group'));
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        if(!Auth::user()->can('edit news')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'slug' => 'required'
        ]);
        $request['slug'] = Str::slug($request->slug);
        if ($serviceCategory->update($request->all())) {
            return Redirect::route('admin.service-categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        $item = ServiceCategory::findOrFail($id);
        $clone = $item->duplicate();
        $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        if ($clone->save()) {
            return Redirect::route('admin.service-categories.edit', ['category' => $clone])->with('success', trans('daran::common.duplication_success'));
        } else {
            return Redirect::route('admin.service-categories.index')->with('error', trans('daran::common.duplication_fail'));
        }
    }
}
