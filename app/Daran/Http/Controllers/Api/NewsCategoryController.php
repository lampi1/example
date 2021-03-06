<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Daran\Models\NewsCategory;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class NewsCategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = NewsCategory::select('id','name','priority')->when($request->filled('lang'),function($q) use($request){
            return $q->where('locale',$request->lang);
        });

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('name','like','%'.$request->get('q').'%');
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

        $newsCategory = NewsCategory::find($id);
        $newsCategory->priority = $new_index;
        $newsCategory->save();

        if($old_index < $new_index){
            if(($new_index-$old_index) == 1){
                //$newsCategories = NewsCategory::where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('news_categories')->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }else{
                //$newsCategories = NewsCategory::where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('news_categories')->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }
        }else{
            if(($old_index-$new_index) == 1){
                //$newsCategories = NewsCategory::where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('news_categories')->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }else{
                //$newsCategories = NewsCategory::where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('news_categories')->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }

        }

        // foreach ($newsCategories as $newsCategory) {
        //     $newsCategory->priority = ($old_index < $new_index) ? $newsCategory->priority-1 : $newsCategory->priority+1;
        //     $newsCategory->save();
        // }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete news')){
        //     abort(503);
        // }

        $newsCategory = NewsCategory::findOrFail($id);
        $success = $newsCategory->delete();

        return response()->json([
            'success' => $success
        ]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $pc = NewsCategory::create([
            'name' => $request->name,
            'locale' => $request->locale,
            'locale_group' => Str::random(20),
        ]);

        if($pc){
            return response()->json([
                'success' => true,
                'category' => $pc
            ]);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

}
