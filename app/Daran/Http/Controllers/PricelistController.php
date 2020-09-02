<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Family;
use App\Daran\Models\Category;
use App\Daran\Models\Pricelist;
use App\User;
use App\Daran\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Redirect;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PricelistController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read item')){
            abort(503);
        }

        $items = Pricelist::orderBy('name')->paginate(50);

        return view('daran::pricelists.index',compact('items'));
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create item')){
            abort(503);
        }

        $pricelist = new Pricelist();
        $pricelist->id = 0;

        return view('daran::pricelists.create', compact('pricelist'));
    }

    public function edit(Pricelist $pricelist)
    {
        if(!Auth::user()->can('edit item')){
            abort(503);
        }

        return view('daran::pricelists.create', compact('pricelist'));
    }
}
