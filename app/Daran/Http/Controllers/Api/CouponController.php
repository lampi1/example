<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Daran\Models\Coupon;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function getCoupons(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = DB::table('coupons')->select('coupons.id','coupons.name','coupons.code','category_translations.name as category','users.surname as user','family_translations.name as family')
        ->leftJoin('category_translations','category_translations.category_id','=','coupons.category_id')
        ->leftJoin('family_translations','family_translations.family_id','=','coupons.family_id')
        ->leftJoin('users','users.id','=','coupons.user_id')
        ->where('category_translations.locale',session('working_lang', Lang::getLocale()))
        ->where('family_translations.locale',session('working_lang', Lang::getLocale()));

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('coupons.name','like','%'.$request->get('q').'%')->orWhere('coupons.code','like','%'.$request->get('q').'%');
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

        $coupon = Coupon::findOrFail($id);
        if($coupon->date_start < now()){
            return response()->json([
                'success' => false,
                'message' => trans('daran::coupon.delete_date_before_today')
            ]);
        }



        $success = $coupon->delete();

        return response()->json([
            'success' => $success
        ]);
    }


}
