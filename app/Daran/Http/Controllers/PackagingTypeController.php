<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\PackagingType;
use App\Daran\Models\Redirection;
use App\Daran\Http\Requests\PackagingTypeRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class PackagingTypeController extends Controller
{
    public function index(Request $request)
    {
        return view('daran::packaging-types.index');
    }

    public function create(Request $request)
    {
        $packagingType = new PackagingType();
        return view('daran::packaging-types.create', compact('packagingType'));
    }

    public function store(PackagingTypeRequest $request)
    {
        $packagingType = new PackagingType();
        $packagingType->name = $request->name;

        if ($packagingType->save()) {
            return Redirect::route('admin.packaging-types.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(PackagingType $packagingType)
    {
        return view('daran::packaging-types.edit', compact('packagingType'));
    }

    public function update(PackagingTypeRequest $request, PackagingType $packagingType)
    {
        $packagingType->name = $request->name;

        if ($packagingType->save()) {
            return Redirect::route('admin.packaging-types.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }
}
