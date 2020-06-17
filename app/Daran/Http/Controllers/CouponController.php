<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Models\Coupon;
use App\Daran\Models\Category;
use App\Daran\Models\Item;
use App\Daran\Models\Family;
use App\Models\User;
use App\Daran\Http\Requests\CouponRequest;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        return view('daran::coupons.index');
    }

    public function create()
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        $categories = Category::orderBy('code')->get();
        $families = Family::orderBy('code')->get();
        $users = User::orderBy('surname')->get();
        $coupon = new Coupon();
        $coupon->amount = 0;
        $coupon->discount = 0;
        $coupon->date_start = Carbon::tomorrow()->format('d/m/Y');

        return view('daran::coupons.create',compact('coupon','families','categories','users'));
    }

    public function store(CouponRequest $request)
    {
        $coupon = Coupon::create($request->all());

        $code = $coupon->code ?? Str::random(12);
        while ($coupon->codeExists($code)){
            $code = Str::random(12);
        }
        $coupon->code = $code;

        if($coupon->save()){
            return Redirect::route('admin.coupons.index')->with('success', trans('daran::message.success.create'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Coupon $coupon)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        $categories = Category::orderBy('code')->get();
        $families = Family::orderBy('code')->get();
        $users = User::orderBy('surname')->get();

        return view('daran::coupons.edit',compact('coupon','categories','families','users'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        if($coupon->update($request->all())){
            return Redirect::route('admin.coupons.index')->with('success', trans('daran::message.success.update'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function destroy(Coupon $coupon)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        if ($coupon->delete()) {
            return Redirect::route('admin.coupons.index')->with('success', trans('item/message.success.delete'));
        } else {
            return Redirect::route('admin.coupons.index')->with('error', trans('item/message.error.delete'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('edit item')){
             abort(503);
         }

         $coupon = Coupon::findOrFail($id);
         $clone = $coupon->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.coupons.edit', ['coupon' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.coupons.index')->with('error', trans('daran::message.error.clone'));
         }
    }

}
