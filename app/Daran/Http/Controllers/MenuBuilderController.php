<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Menu;
use App\Daran\Models\MenuItem;
use App\Daran\Models\MenuResource;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;

class MenuBuilderController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('manage menu')){
            abort(503);
        }

        $menus = Menu::get();

        return view('daran::menus.index',compact('menus'));
    }

    public function edit(Menu $menu)
    {
        if(!Auth::user()->can('manage menu')){
            abort(503);
        }
        $menu->load('menu_items');
        $resources = MenuResource::where('enabled',1)->get();
        $linkTypes = array();
        foreach ($resources as $l) {
            $linkTypes[] = array('id'=>$l->id, 'name'=>$l->name, 'model'=>$l->model, 'route'=>$l->route);
        }

        return view('daran::menus.edit', compact('menu', 'linkTypes'));
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
        $old_slug = array();

        foreach ($langs as $lang) {
            $name_field = 'name_'.$lang;
            $meta_title_field = 'meta_title_'.$lang;
            $meta_description_field = 'meta_description_'.$lang;
            $og_title_field = 'og_title_'.$lang;
            $og_description_field = 'og_description_'.$lang;
            $fields[$lang] = ['name' => $request->$name_field, 'meta_title' => $request->$meta_title_field,'meta_description' => $request->$meta_description_field, 'og_title' => $request->$og_title_field,'og_description' => $request->$og_description_field];
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

        /*
        if(count($old_slug) > 0){ //slug aggiornati: imposto redirect 301
            foreach($old_slug as $slg){
                $translator = app('translator');
                $vecchio = LaravelLocalization::getURLFromRouteNameTranslated($slg['lang'],'routes.collection.view',['gender'=>$translator->trans('routes.permalinks.uomo', [], $slg['lang']),'family'=>$slg['old_slug']]);
                $nuovo = LaravelLocalization::getURLFromRouteNameTranslated($slg['lang'],'routes.collection.view',['gender'=>$translator->trans('routes.permalinks.uomo', [], $slg['lang']),'family'=>$slg['new_slug']]);
                Redirection::create(['name'=>Str::random(40),'code'=>'301','from_uri'=>$vecchio,'to_uri'=>$nuovo]);
                $vecchio = LaravelLocalization::getURLFromRouteNameTranslated($slg['lang'],'routes.collection.view',['gender'=>$translator->trans('routes.permalinks.donna', [], $slg['lang']),'family'=>$slg['old_slug']]);
                $nuovo = LaravelLocalization::getURLFromRouteNameTranslated($slg['lang'],'routes.collection.view',['gender'=>$translator->trans('routes.permalinks.donna', [], $slg['lang']),'family'=>$slg['new_slug']]);
                Redirection::create(['name'=>Str::random(40),'code'=>'301','from_uri'=>$vecchio,'to_uri'=>$nuovo]);
            }
        }*/

        if ($category->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

}
