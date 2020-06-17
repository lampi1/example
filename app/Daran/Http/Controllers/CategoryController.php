<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Family;
use App\Daran\Models\Category;
use App\Daran\Models\CategoryTranslation;
use App\Daran\Models\Redirection;
use App\Daran\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }

        return view('daran::categories.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $category = new Category();
        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $fields[$lang] = ['name' => '','description' => '','meta_description' => '','meta_title' => '','og_description' => '','og_title' => ''];
        }
        $category->fill($fields);
        $families = Family::orderBy('priority')->get();

        return view('daran::categories.create', compact('category','langs','families'));
    }

    public function store(CategoryRequest $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $category = new Category($request->only('code','family_id'));
        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $name_field = 'name_'.$lang;
            $description_field = 'description_'.$lang;
            $meta_title_field = 'meta_title_'.$lang;
            $meta_description_field = 'meta_description_'.$lang;
            $og_title_field = 'og_title_'.$lang;
            $og_description_field = 'og_description_'.$lang;
            $fields[$lang] = ['name' => $request->$name_field,'description' => $request->$description_field,'meta_title' => $request->$meta_title_field,'meta_description' => $request->$meta_description_field,'og_title' => $request->$og_title_field,'og_description' => $request->$og_description_field];
        }
        $category->fill($fields);
        $category->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-'.$category->id.'_'.uniqid().'.'.$extension;
            $category->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-mobile_'.$category->id.'_'.uniqid().'.'.$extension;
            $category->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-mobile_'.$category->id.'_'.uniqid().'.'.$extension;
            $category->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if ($category->save()) {
            return Redirect::route('admin.categories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Category $category)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }
        $langs = config('app.available_translations');
        $families = Family::orderBy('priority')->get();

        return view('daran::categories.edit', compact('category', 'langs','families'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        $category->code = $request->code;
        $category->family_id = $request->family_id;
        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $name_field = 'name_'.$lang;
            $description_field = 'description_'.$lang;
            $meta_title_field = 'meta_title_'.$lang;
            $meta_description_field = 'meta_description_'.$lang;
            $og_title_field = 'og_title_'.$lang;
            $og_description_field = 'og_description_'.$lang;
            $fields[$lang] = ['name' => $request->$name_field,'description' => $request->$description_field, 'meta_title' => $request->$meta_title_field,'meta_description' => $request->$meta_description_field, 'og_title' => $request->$og_title_field,'og_description' => $request->$og_description_field];
        }
        $category->fill($fields);
        $filename = array();

        if ($request->hasFile('image')) {
            $filename[] = $category->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-'.$category->id.'_'.uniqid().'.'.$extension;
            $category->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $category->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-mobile_'.$category->id.'_'.uniqid().'.'.$extension;
            $category->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if ($category->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create item')){
             abort(503);
         }

         $category = Category::findOrFail($id);
         $clone = $category->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.categories.edit', ['category' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.categories.index')->with('error', trans('daran::message.error.clone'));
         }
    }
}
