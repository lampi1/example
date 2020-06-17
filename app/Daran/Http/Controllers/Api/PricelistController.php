<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Models\User;
use App\Daran\Models\Family;
use App\Daran\Models\Category;
use App\Daran\Models\Item;
use App\Daran\Models\Pricelist;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class PricelistController extends Controller
{
    public function getUsers(Request $request)
    {
        $items = User::where('active','1')
            ->when($request->filled('q'), function($q) use($request){
                $q->where(function($query) use($request){
                    $query->where('name','LIKE','%'.$request->q.'%')->orWhere('business','LIKE','%'.$request->q.'%');
                });
            })->orderBy('business')->take(10)->get();

        return response()->json([
            'users' => $items
        ]);
    }

    public function getFamilies(Request $request)
    {
        $items = Family::when($request->filled('q'), function($q) use($request){
            $q->whereTranslationLike('name','%'.$request->q.'%');
        })->orderByTranslation('name')->take(10)->get();

        return response()->json([
            'families' => $items
        ]);
    }

    public function getCategories(Request $request)
    {
        $items = Category::when($request->filled('family'), function($q) use($request){
            $q->where('family_id',$request->family);
        })->when($request->filled('q'), function($q) use($request){
            $q->whereTranslationLike('name','%'.$request->q.'%');
        })->orderByTranslation('name')->take(10)->get();

        return response()->json([
            'categories' => $items
        ]);
    }


    public function getItems(Request $request)
    {
        if ($request->category == '-1') {
            $items = Item::get();
        } else{
            $items = Item::where('category_id', $request->category)->get();
        }

        return response()->json([
            'items' => $items
        ]);
    }

    public function save(Request $request)
    {
        $plist = ($request->pricelist['id'] == 0) ? new Pricelist() : Pricelist::findOrFail($request->pricelist['id']);
        $plist->name = $request->pricelist['name'];
        $plist->save();

        if($request->pricelist['id'] > 0){
            DB::table('users')->where('pricelist_id',$plist->id)->update(['pricelist_id'=>null]);
        }

        $items = array();
        foreach($request->pricelist['selectedItems'] as $item){
            $items[$item['id']] = ['price'=>$item['price']];
        }

        $cats = array();
        foreach($request->pricelist['selectedCategories'] as $item){
            $cats[] = $item['id'];
        }

        $plist->categories()->sync(array_values($cats));
        $plist->items()->sync($items);

        foreach($request->pricelist['users'] as $user){
            User::where('id',$user['id'])->update(['pricelist_id'=>$plist->id]);
        }

        return response()->json([
            'success' => $plist->save()
        ]);
    }

    public function destroy($id)
    {
        $plist = Pricelist::findOrFail($id);
        return response()->json([
            'success' => $plist->delete()
        ]);
    }

    public function show($id)
    {
        $plist = Pricelist::with('categories','users','items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'pricelist' => $plist
        ]);
    }
}
