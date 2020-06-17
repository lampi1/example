<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Family;
use App\Daran\Models\Category;
use App\Daran\Models\Subcategory;
use App\Daran\Models\SubcategoryTranslation;
use App\Daran\Models\Redirection;
use App\Daran\Http\Requests\SubcategoryRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SubcategoryController extends Controller
{

    public function index(Request $request)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }

        return view('daran::subcategories.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $subcategory = new Subcategory();
        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $fields[$lang] = ['name' => '','description' => '','meta_description' => '','meta_title' => '','og_description' => '','og_title' => ''];
        }
        $subcategory->fill($fields);
        $families = Family::with('categories')->orderBy('priority')->get();

        return view('daran::subcategories.create', compact('subcategory','langs','families'));
    }

    public function store(SubcategoryRequest $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $subcategory = new Subcategory($request->only('code','category_id'));
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
        $subcategory->fill($fields);
        $subcategory->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-'.$subcategory->id.'_'.uniqid().'.'.$extension;
            $subcategory->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-mobile_'.$subcategory->id.'_'.uniqid().'.'.$extension;
            $subcategory->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-mobile_'.$subcategory->id.'_'.uniqid().'.'.$extension;
            $subcategory->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if ($subcategory->save()) {
            return Redirect::route('admin.subcategories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Subcategory $subcategory)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }
        $langs = config('app.available_translations');
        $families = Family::with('categories')->orderBy('priority')->get();

        return view('daran::subcategories.edit', compact('subcategory', 'langs','families'));
    }

    public function update(SubcategoryRequest $request, Subcategory $subcategory)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        $subcategory->code = $request->code;
        $subcategory->category_id = $request->category_id;
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
        $subcategory->fill($fields);
        $filename = array();

        if ($request->hasFile('image')) {
            $filename[] = $subcategory->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-'.$subcategory->id.'_'.uniqid().'.'.$extension;
            $subcategory->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $subcategory->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'category-mobile_'.$subcategory->id.'_'.uniqid().'.'.$extension;
            $subcategory->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if ($subcategory->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.subcategories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create item')){
             abort(503);
         }

         $subcategory = Subcategory::findOrFail($id);
         $clone = $subcategory->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.subcategories.edit', ['subcategory' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.subcategories.index')->with('error', trans('daran::message.error.clone'));
         }
    }
}
