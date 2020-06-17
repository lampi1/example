<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Redirection;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use App\Daran\Http\Requests\RedirectionRequest;

class RedirectionController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read redirection')){
            abort(503);
        }

        return view('daran::redirections.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!Auth::user()->can('create redirection')){
            abort(503);
        }

        $redirection = new Redirection();

        return view('daran::redirections.create', compact('redirection'));
    }

    public function store(RedirectionRequest $request)
    {
        if(!Auth::user()->can('create redirection')){
            abort(503);
        }

        $redirection = new Redirection($request->all());

        if ($redirection->save()) {
            return Redirect::route('admin.redirections.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Request $request, Redirection $redirection)
    {
        if(!Auth::user()->can('edit redirection')){
            abort(503);
        }

        return view('daran::redirections.edit', compact('redirection'));
    }


    public function update(RedirectionRequest $request, Redirection $redirection)
    {
        if(!Auth::user()->can('edit redirection')){
            abort(503);
        }

        $redirection->update($request->all());

        if ($redirection->save()) {
            return Redirect::route('admin.redirections.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

}
