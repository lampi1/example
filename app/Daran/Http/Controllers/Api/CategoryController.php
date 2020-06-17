<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Daran\Models\Category;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = DB::table('categories')->select('categories.id','categories.image','categories.code','category_translations.name','categories.priority','family_translations.name as family')
        ->join('category_translations','category_translations.category_id','=','categories.id')
        ->join('families','categories.family_id','=','families.id')
        ->join('family_translations','family_translations.family_id','=','families.id')
        ->where('category_translations.locale',session('working_lang', Lang::getLocale()))
        ->where('family_translations.locale',session('working_lang', Lang::getLocale()));

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('category_translations.name','like','%'.$request->get('q').'%');
        });

        $qb->when($request->filled('family_id'),function($q) use($request){
            return $q->where('categories.family_id',$request->get('family_id'));
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

        $category = Category::find($id);
        $category->priority = $new_index;
        $category->save();

        if($old_index < $new_index){
            if(($new_index-$old_index) == 1){
                $categories = Category::where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->get();
            }else{
                $categories = Category::where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->get();
            }
        }else{
            if(($old_index-$new_index) == 1){
                $categories = Category::where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->get();
            }else{
                $categories = Category::where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->get();
            }

        }

        foreach ($categories as $category) {
            $category->priority = ($old_index < $new_index) ? $category->priority-1 : $category->priority+1;
            $category->save();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete item')){
        //     abort(503);
        // }

        $category = Category::findOrFail($id);
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

         $category = Category::findOrFail($id);
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
