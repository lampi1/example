<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Family;
use App\Daran\Models\FamilyTranslation;
use App\Daran\Models\Redirection;
use App\Daran\Http\Requests\FamilyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FamilyController extends Controller
{

    public function index(Request $request)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }

        return view('daran::families.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $family = new Family();
        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $fields[$lang] = ['name' => '','description' => '','meta_description' => '','meta_title' => '','og_description' => '','og_title' => ''];
        }
        $family->fill($fields);

        return view('daran::families.create', compact('family','langs'));
    }

    public function store(FamilyRequest $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $family = new Family($request->only('code'));
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
        $family->fill($fields);
        $family->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'family-'.$family->id.'_'.uniqid().'.'.$extension;
            $family->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'family-mobile-'.$family->id.'_'.uniqid().'.'.$extension;
            $family->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'family-mobile-'.$family->id.'_'.uniqid().'.'.$extension;
            $family->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if ($family->save()) {
            return Redirect::route('admin.families.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Family $family)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }
        $langs = config('app.available_translations');

        return view('daran::families.edit', compact('family', 'langs'));
    }

    public function update(FamilyRequest $request, Family $family)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        $family->code = $request->code;
        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $name_field = 'name_'.$lang;
            $description_field = 'description_'.$lang;
            $meta_title_field = 'meta_title_'.$lang;
            $meta_description_field = 'meta_description_'.$lang;
            $og_title_field = 'og_title_'.$lang;
            $og_description_field = 'og_description_'.$lang;
            $fields[$lang] = ['name' => $request->$name_field, 'description' => $request->$description_field, 'meta_title' => $request->$meta_title_field,'meta_description' => $request->$meta_description_field, 'og_title' => $request->$og_title_field,'og_description' => $request->$og_description_field];
        }
        $family->fill($fields);
        $filename = array();

        if ($request->hasFile('image')) {
            $filename[] = $family->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'family-'.$family->id.'_'.uniqid().'.'.$extension;
            $family->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $family->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'family-mobile-'.$family->id.'_'.uniqid().'.'.$extension;
            $family->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if ($family->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.families.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create item')){
             abort(503);
         }

         $family = Family::findOrFail($id);
         $clone = $family->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.families.edit', ['family' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.families.index')->with('error', trans('daran::message.error.clone'));
         }
    }
}
