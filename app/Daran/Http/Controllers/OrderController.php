<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Order;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Notifications\OrderReady;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }

        return view('daran::orders.index');
    }

    public function show(Order $order)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }
        $order->load('order_details','order_details.item','order_details.item.images');
        return view('daran::orders.show',compact('order'));
    }

    public function updateState($id, $field, $value)
    {
        $order = Order::findOrFail($id);
        $order->$field = $value;
        if($field == 'status' && $value == 'payed'){
            $order->payed_at = Carbon::now();
        }
        if($field == 'ship_status' && $value == 'delivered'){
            $order->delivered_at = Carbon::now();
        }
        if($field == 'ship_status' && $value == 'request_delivery'){
            $order->user->notify(new OrderReady($order));
        }
        if($field == 'ship_status' && $value == 'new'){
            $order->goods_amount = $order->total;
        }
        $order->save();

        return Redirect::route('admin.orders.show',['id'=>$id])->with('success', trans('daran::message.success.update'));
    }
}
