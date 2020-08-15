<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Daran\Models\Subcategory;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class SubcategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = DB::table('subcategories')->select('subcategories.id','subcategories.image','subcategories.code','subcategory_translations.name','subcategories.priority','category_translations.name as category')
        ->join('subcategory_translations','subcategory_translations.subcategory_id','=','subcategories.id')
        ->join('categories','subcategories.category_id','=','categories.id')
        ->join('category_translations','category_translations.category_id','=','categories.id')
        ->where('subcategory_translations.locale',session('working_lang', Lang::getLocale()))
        ->where('category_translations.locale',session('working_lang', Lang::getLocale()));

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('subcategory_translations.name','like','%'.$request->get('q').'%');
        });

        $qb->when($request->filled('category_id'),function($q) use($request){
            return $q->where('subcategories.category_id',$request->get('category_id'));
        });

        $sort_array = explode('|',$sort);
        if(count($sort_array) == 2){
            $qb->orderBy($sort_array[0],$sort_array[1]);
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

    public function reorder(Request $request)
    {
        $id = trim($request->id);
        $new_index = trim($request->new_index);
        $old_index = trim($request->old_index);

        $category = Subcategory::find($id);
        $category->priority = $new_index;
        $category->save();
        $category_id = $category->category_id;

        if($old_index < $new_index){
            if(($new_index-$old_index) == 1){
                //$categories = Subcategory::where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('subcategories')->where('category_id',$category_id)->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }else{
                //$categories = Subcategory::where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('subcategories')->where('category_id',$category_id)->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }
        }else{
            if(($old_index-$new_index) == 1){
                //$categories = Subcategory::where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('subcategories')->where('category_id',$category_id)->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }else{
                //$categories = Subcategory::where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('subcategories')->where('category_id',$category_id)->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }

        }

        // foreach ($categories as $category) {
        //     $category->priority = ($old_index < $new_index) ? $category->priority-1 : $category->priority+1;
        //     $category->save();
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

        $category = Subcategory::findOrFail($id);
        $filename = $category->image;
        $success = $category->delete();
        if($success){
            $this->deleteFiles($filename);
        }

        return response()->json([
            'success' => $success
        ]);
    }

    public function deleteImage(Request $request,$id)
    {
         // if(!Auth::user()->can('edit item')){
         //     abort(503);
         // }

         $category = Subcategory::findOrFail($id);
         $type = $request->get('type','standard');
         if($type == 'mobile'){
             $filename = $category->image_sm;
             $category->image_sm = null;
         }else{
             $filename = $category->image;
             $category->image = null;
         }

         if ($category->save()) {
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
