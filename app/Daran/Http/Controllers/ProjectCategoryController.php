<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\ProjectCategory;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectCategoryController extends Controller
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

        return view('daran::project-categories.index');
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
        $projectCategory = new ProjectCategory();
        $projectCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::project-categories.create', compact('projectCategory', 'locales', 'locale_group'));
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

        $projectCategory = new ProjectCategory($request->all());
        $projectCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $projectCategory->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($projectCategory->save()){
            return Redirect::route('admin.project-categories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }


    public function edit(ProjectCategory $projectCategory)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }
        $locale_group = $projectCategory->locale_group;

        return view('daran::project-categories.edit', compact('projectCategory', 'locales','locale_group'));
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'slug' => 'required'
        ]);
        $request['slug'] = Str::slug($request->slug);
        if ($projectCategory->update($request->all())) {
            return Redirect::route('admin.project-categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
        if(!Auth::user()->can('create page')){
            abort(503);
        }

        $category = ProjectCategory::findOrFail($id);
        $clone = $category->duplicate();
        $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        if ($clone->save()) {
            return Redirect::route('admin.project-categories.edit', ['project_category' => $clone->id])->with('success', trans('daran::message.success.clone'));
        } else {
            return Redirect::route('admin.project-categories.index')->with('error', trans('daran::message.error.clone'));
        }
     }
}
