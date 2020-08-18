<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\PackagingType;
use App\Daran\Models\Family;
use App\Daran\Models\Category;
use App\Daran\Models\Item;
use App\Daran\Models\ItemAvailablePackagingType;
use App\Daran\Models\ItemTranslation;
use App\Daran\Models\Redirection;
use App\Daran\Models\OrderDetail;
use App\Daran\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }

        return view('daran::items.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $item = new Item();
        $item->published = 0;
        $item->featured = 0;
        $item->price = 0;
        $item->discount = 0;
        $item->stock = 1;
        $item->minimun = 1;
        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $fields[$lang] = ['description'=>'','meta_description' => '','meta_title' => '','og_description' => '','og_title' => '','material' => '', 'color' => '', 'sizes' => ''];
        }
        $item->fill($fields);

        $packagingTypes = PackagingType::get();
        $families = Family::with('categories','categories.subcategories')->orderBy('priority')->get();
        return view('daran::items.create', compact('item','langs','families','packagingTypes'));
    }

    public function store(ItemRequest $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $item = new Item($request->only('code','family_id','category_id','price','discount','stock','minimun','weight','subcategory_id','name','last','riporto','sole_last','sole_height','rotella'));
        $item->published = $request->has('published') ? $request->published : 0;
        $item->featured = $request->has('featured') ? $request->featured : 0;
        $item->is_new = $request->has('is_new') ? $request->is_new : 0;

        $langs = config('app.available_translations');
        $fields = array();

        foreach ($langs as $lang) {
            $descr_field = 'description_'.$lang;
            $meta_title_field = 'meta_title_'.$lang;
            $meta_description_field = 'meta_description_'.$lang;
            $og_title_field = 'og_title_'.$lang;
            $og_description_field = 'og_description_'.$lang;
            $material_field = 'material_'.$lang;
            $color_field = 'color_'.$lang;
            $sizes_field = 'sizes_'.$lang;

            $fields[$lang] = ['description' => $request->$descr_field,'meta_title' => $request->$meta_title_field,'meta_description' => $request->$meta_description_field,'og_title' => $request->$og_title_field,'og_description' => $request->$og_description_field,'material' => $request->$material_field, 'color' => $request->$color_field, 'sizes' => $request->$sizes_field];
        }
        $item->fill($fields);

        if ($request->hasFile('datasheet')) {
            $file = $request->file('datasheet');
            $extension = $file->getClientOriginalExtension() ?: 'pdf';
            $nome_originale = Str::slug($item->name).'.'.$extension;
            $item->datasheet = $this->saveFile($file,$nome_originale);
        }

        if ($item->save()) {

            $packaging_type_ids = $request->packaging_type_id;
            $base_packaging_type_ids = $request->base_packaging_type_id;
            $qtys = $request->qty;
            $prices = $request->prices;
    
            foreach($qtys as $key => $qty){
                if($qty > 0){
                    $itemAvailablePackagingType = new ItemAvailablePackagingType();
                    $itemAvailablePackagingType->item_id = $item->id;
                    $itemAvailablePackagingType->packaging_type_id = $packaging_type_ids[$key];
                    $itemAvailablePackagingType->base_packaging_type_id = $base_packaging_type_ids[$key];
                    $itemAvailablePackagingType->qty = $qty;
                    $itemAvailablePackagingType->price = $prices[$key];
                    $itemAvailablePackagingType->save();    
                }
            }
            
            return Redirect::route('admin.items.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Item $item)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }
        $langs = config('app.available_translations');
        $families = Family::with('categories','categories.subcategories')->orderBy('priority')->get();
        $packagingTypes = PackagingType::get();

        $item->load('related');
        return view('daran::items.edit', compact('item', 'langs','families','packagingTypes'));
    }

    public function update(ItemRequest $request, Item $item)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        $item->update($request->only('code','family_id','category_id','price','discount','stock','minimun','weight','subcategory_id','name','last','riporto','sole_last','sole_height','rotella'));
        $item->published = $request->has('published') ? $request->published : 0;
        $item->featured = $request->has('featured') ? $request->featured : 0;
        $item->is_new = $request->has('is_new') ? $request->is_new : 0;
        $langs = config('app.available_translations');
        $fields = array();
        $old_slug = array();

        foreach ($langs as $lang) {
            $descr_field = 'description_'.$lang;
            $meta_title_field = 'meta_title_'.$lang;
            $meta_description_field = 'meta_description_'.$lang;
            $og_title_field = 'og_title_'.$lang;
            $og_description_field = 'og_description_'.$lang;
            $material_field = 'material_'.$lang;
            $color_field = 'color_'.$lang;
            $sizes_field = 'sizes_'.$lang;
            $fields[$lang] = ['description' => $request->$descr_field, 'meta_title' => $request->$meta_title_field,'meta_description' => $request->$meta_description_field, 'og_title' => $request->$og_title_field,'og_description' => $request->$og_description_field,'material' => $request->$material_field,'color' => $request->$color_field,'sizes' => $request->$sizes_field];
        }
        $item->fill($fields);

        if ($request->hasFile('datasheet')) {
            $this->deleteFiles([$item->datasheet]);
            $file = $request->file('datasheet');
            $extension = $file->getClientOriginalExtension() ?: 'pdf';
            $nome_originale = Str::slug($item->name).'.'.$extension;
            $item->datasheet = $this->saveFile($file,$nome_originale);
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

        $item->available_packaging_types()->delete();

        $packaging_type_ids = $request->packaging_type_id;
        $base_packaging_type_ids = $request->base_packaging_type_id;
        $qtys = $request->qty;
        $prices = $request->price;

        foreach($qtys as $key => $qty){
            if($qty > 0 || $qty){
                $itemAvailablePackagingType = new ItemAvailablePackagingType();
                $itemAvailablePackagingType->item_id = $item->id;
                $itemAvailablePackagingType->packaging_type_id = $packaging_type_ids[$key];
                $itemAvailablePackagingType->base_packaging_type_id = $base_packaging_type_ids[$key];
                $itemAvailablePackagingType->qty = $qty;
                $itemAvailablePackagingType->price = $prices[$key];
                $itemAvailablePackagingType->save();    
            }
        }

        if ($item->save()) {
            return Redirect::route('admin.items.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create item')){
             abort(503);
         }

         $item = Item::findOrFail($id);
         $clone = $item->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.items.edit', ['id' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.items.index')->with('error', trans('daran::message.error.clone'));
         }
    }

    public function editRelated(Request $request, $id)
    {
        // if(!Auth::user()->can('edit item')){
        //     abort(503);
        // }

        return view('daran::items.related',compact('id'));
    }

    public function editImages(Request $request, $id)
    {
        // if(!Auth::user()->can('edit item')){
        //     abort(503);
        // }
        $this->getPath();
        return view('daran::items.images',compact('id'));
    }

    public function show(Item $item)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }

        return view('daran::items.show',compact('item'));
    }

    public function discount()
    {
        $categories = Category::orderBy('code')->get();
        $families = Family::orderBy('priority')->get();

        return view('daran::items.discount', compact('categories','families'));
    }

    public function saveDiscount(Request $request)
    {
        DB::table('items')->when($request->filled('category_id'), function($q) use($request){
            $q->where('category_id',$request->category_id);
        })->when($request->filled('family_id'), function($q) use($request){
            $q->where('family_id',$request->family_id);
        })->update(['discount'=>$request->discount]);

        return Redirect::route('admin.items.index')->with('success', trans('item/message.success.update'));
    }

    public function editColors(Request $request, $id)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        return view('daran::items.colors',compact('id'));
    }
}
