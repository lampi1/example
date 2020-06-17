<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Daran\Http\Controllers\Controller;
use App\Models\User;
use App\Daran\Models\TypeTranslation;
use App\Daran\Models\Redirection;
use App\Daran\Models\Pricelist;
use App\Daran\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Mails\UserRegistration;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if(!Auth::user()->can('read user')){
            abort(503);
        }

        return view('daran::users.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $user = new User();
        $pricelists = Pricelist::orderBy('name')->get();
        $langs = config('app.available_translations');
        return view('daran::users.create', compact('user','langs','pricelists'));
    }

    public function store(UserRequest $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        if(!$request->filled('password')){
            return Redirect::back()->withInput()->with('error', trans('daran::user.missing_password'));
        }

        $user = new User($request->except('password','password_confirmation'));
        $user->password = Hash::make($request->password);
        Mail::to($user->email)->send(new UserRegistration($user,$request->password));
        if ($user->save()) {
            return Redirect::route('admin.users.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(User $user)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }
        $langs = config('app.available_translations');
        $pricelists = Pricelist::orderBy('name')->get();
        return view('daran::users.edit', compact('user','langs','pricelists'));
    }

    public function update(UserRequest $request, User $user)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        $user->update($request->except('password','password_confirmation'));
        if($request->filled('password')){
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            return Redirect::route('admin.users.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create item')){
             abort(503);
         }

         $user = User::findOrFail($id);
         $clone = $user->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.users.edit', ['id' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.users.index')->with('error', trans('daran::message.error.clone'));
         }
    }
}
