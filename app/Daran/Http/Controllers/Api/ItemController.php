<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Daran\Models\Item;
use App\Daran\Models\ItemImage;
use App\Daran\Models\ItemColor;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function getItems(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = DB::table('items')->select('items.id','items.code','items.published','items.name','items.priority','family_translations.name as family','item_images.filename as image')
        ->join('item_translations','item_translations.item_id','=','items.id')
        ->leftJoin('item_images', function($q){
            $q->on('item_images.item_id','=','items.id')->where('item_images.priority', 0);
        })
        ->join('families','items.family_id','=','families.id')
        ->join('family_translations', function($q){
            $q->on('family_translations.family_id','=','families.id')->where('family_translations.locale',session('working_lang', Lang::getLocale()));
        })
        ->where('item_translations.locale',session('working_lang', Lang::getLocale()))
        //

        ->whereNull('items.deleted_at');

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('items.name','like','%'.$request->get('q').'%');
        });

        $qb->when($request->filled('family_id'),function($q) use($request){
            return $q->where('items.family_id',$request->get('family_id'));
        });

        $sort_array = explode('|',$sort);
        if(count($sort_array) == 2){
            $qb->orderBy($sort_array[0],$sort_array[1]);
            if($sort_array[0] != 'name'){
                $qb->orderBy('name','asc');
            }
        }

        if ($limit) {
            $paginator = $qb->paginate($limit);
            //dd($paginator);
            $links = array(
                'pagination' => array(
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'self_page_url' => $paginator->url($paginator->currentPage()),
                    'prev_page_url' => $paginator->previousPageUrl(),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'from' => ($paginator->currentPage()-1) * $paginator->perPage() + 1,
                    'to' => ($paginator->currentPage() * $paginator->perPage()) > $paginator->total() ? $paginator->total() : $paginator->currentPage() * $paginator->perPage()
                )
            );
            $items = $paginator->items();
        } else {
            $links = array();
            $items = $qb->get();
        }

        return response()->json([
            'links' => $links,
            'data' => $items,
        ]);
    }

    public function changeState(int $id)
    {
        // if(!Auth::user()->can('edit item')){
        //     abort(503);
        // }

        $item = Item::findOrFail($id);
        $item->published = !$item->published;
        $success = $item->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function reorder(Request $request)
    {
        $id = trim($request->id);
        $new_index = trim($request->new_index);
        $old_index = trim($request->old_index);

        $item = Item::find($id);
        $item->priority = $new_index;
        $item->save();

        if($old_index < $new_index){
            if(($new_index-$old_index) == 1){
                //$items = Item::where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->whereNull('deleted_at')->get();
                DB::table('items')->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }else{
                //$items = Item::where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->whereNull('deleted_at')->get();
                DB::table('items')->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }
        }else{
            if(($old_index-$new_index) == 1){
                //$items = Item::where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->whereNull('deleted_at')->get();
                DB::table('items')->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }else{
                //$items = Item::where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->whereNull('deleted_at')->get();
                DB::table('items')->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }

        }

        // foreach ($items as $item) {
        //     $item->priority = ($old_index < $new_index) ? $item->priority-1 : $item->priority+1;
        //     $item->save();
        // }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete item')){
        //     abort(503);
        // }

        $item = Item::findOrFail($id);
        $success = $item->delete();

        return response()->json([
            'success' => $success
        ]);
    }

    public function getItem($id)
    {
        $item = Item::with('related','images','related.images')->findOrFail($id);
        return response()->json([
            'success' => true,
            'item' => $item
        ]);
    }

    public function removeRelated(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->related()->detach($request->related_id);
        return response()->json([
            'success' => true
        ]);
    }

    public function addRelated(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $new_item = Item::with('related','images','related.images')->findOrFail($request->related_id);
        $item->related()->attach($request->related_id);
        return response()->json([
            'success' => true,
            'item' => $new_item
        ]);
    }

    public function deleteImage(Request $request,$id)
    {
         // if(!Auth::user()->can('edit item')){
         //     abort(503);
         // }

         $filename = array();
         $iimage = ItemImage::where('item_id',$id)->where('id',$request->image_id)->firstOrFail();
         $filename[] = $iimage->filename;
         $iimage->filename = null;
         $filename[] = $iimage->filename_sm;
         $iimage->filename_sm = null;

         if ($iimage->delete()) {
             $this->deleteFiles($filename);
             return response()->json([
                 'success' => true
             ]);
         } else {
             return response()->json([
                 'success' => false
             ]);
         }
    }

    public function addImage(Request $request,$id)
    {
         // if(!Auth::user()->can('edit item')){
         //     abort(503);
         // }

         $priority = DB::table('item_images')->where('item_id',$id)->max('priority');//ItemImage::where('item_id',$id)->max('priority');

         $image = new ItemImage();
         $image->item_id = $id;
         $image->priority = $priority ? ++$priority : 0;
         $image->save();

         $item = Item::findOrFail($id);
         $file = $request->file('filepond');
         $extension = $file->getClientOriginalExtension() ?: 'png';
         $nome = Str::slug($item->name.'-'.$item->code).'_'.uniqid().'.'.$extension;
         $img = $this->makeImage($file);
         $image->filename =  $this->saveImage($img,$nome, config('daran.images.breakpoints.standard'));
         $nome = Str::slug($item->name).'-mobile_'.uniqid().'.'.$extension;
         $image->filename_sm = $this->saveImage($img,$nome, config('daran.images.breakpoints.mobile'), true);

         if ($image->save()) {
             return response()->json([
                 'success' => true
             ]);
         } else {
             return response()->json([
                 'success' => false
             ]);
         }
    }

    public function reorderImages(Request $request,$id)
    {
        for($i=0;$i<count($request->order);$i++){
            $item = ItemImage::find($request->order[$i]['id']);
            $item->priority = $request->order[$i]['priority'];
            $item->save();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function addColor(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        if($request->filled('id') && $request->id > 0){
            $filename = array();
            $new_color = ItemColor::findOrFail($request->id);
            $new_color->name = $request->name;
            if ($request->hasFile('file')) {
                $filename[] = $new_color->filename;
                $filename[] = $new_color->filename_sm;

                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension() ?: 'png';
                $nome = Str::slug($new_color->name).'_'.uniqid().'.'.$extension;
                $img = $this->makeImage($file);
                $new_color->filename =  $this->saveImage($img,$nome, config('daran.images.breakpoints.standard'));
                $nome = Str::slug($new_color->name).'-mobile_'.uniqid().'.'.$extension;
                $new_color->filename_sm = $this->saveImage($img,$nome, config('daran.images.breakpoints.mobile'), true);
            }
            if($new_color->save()){
                $this->deleteFiles($filename);
            }
        }else{
            $new_color = ItemColor::create([
                'item_id' => $id,
                'name' => $request->name
            ]);

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $nome = Str::slug($new_color->name).'_'.uniqid().'.'.$extension;
            $img = $this->makeImage($file);
            $new_color->filename =  $this->saveImage($img,$nome, config('daran.images.breakpoints.standard'));
            $nome = Str::slug($new_color->name).'-mobile_'.uniqid().'.'.$extension;
            $new_color->filename_sm = $this->saveImage($img,$nome, config('daran.images.breakpoints.mobile'), true);
            $new_color->save();
        }


        return response()->json([
            'success' => true,
            'color' => $new_color
        ]);
    }

    public function deleteColor(Request $request,$id)
    {
         $filename = array();
         $color = ItemColor::findOrFail($id);
         $filename[] = $color->filename;
         $color->filename = null;
         $filename[] = $color->filename_sm;
         $color->filename_sm = null;

         if ($color->delete()) {
             $this->deleteFiles($filename);
             return response()->json([
                 'success' => true
             ]);
         } else {
             return response()->json([
                 'success' => false
             ]);
         }
    }
}
