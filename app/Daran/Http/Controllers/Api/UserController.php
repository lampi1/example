<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Models\User;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = DB::table('users')->select('users.id','business','email','name','active');

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('business','like','%'.$request->get('q').'%');
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

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete item')){
        //     abort(503);
        // }

        $type = User::findOrFail($id);
        $filename = $type->image;
        $success = $type->delete();

        if($success){
            $this->deleteFiles($filename);
        }

        return response()->json([
            'success' => $success
        ]);
    }

    public function changeState(int $id)
    {
        // if(!Auth::user()->can('edit item')){
        //     abort(503);
        // }

        $item = User::findOrFail($id);
        $item->active = !$item->active;
        $success = $item->save();

        return response()->json([
            'success' => $success
        ]);
    }

}
