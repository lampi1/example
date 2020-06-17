<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Daran\Models\Order;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = DB::table('orders')->select('orders.id','orders.uuid','orders.status','orders.created_at','orders.total','users.email as email')
        ->leftJoin('users','orders.user_id','=','users.id')
        ->whereNull('orders.deleted_at');

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('orders.number','like','%'.$request->get('q').'%');
        });

        $qb->when($request->filled('status'),function($q) use($request){
            return $q->where('orders.status',$request->get('status'));
        });

        $qb->when($request->filled('user_id'),function($q) use($request){
            return $q->where('orders.user_id',$request->get('user_id'));
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
}
