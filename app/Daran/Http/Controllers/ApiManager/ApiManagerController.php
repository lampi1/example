<?php

namespace App\Daran\Http\Controllers\ApiManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Daran\Models\Item;
use App\Daran\Models\Category;
use App\Daran\Models\ItemSize;
use App\Http\Controllers\Controller;

class ApiManagerController extends Controller
{
    public function updateInfo(Request $request, $code)
    {
        // if(!Auth::user()->can('delete event')){
        //     abort(503);
        // }
        $item = Item::withTrashed()->where('code',$code)->first();
        if(!$item){
            $item = new Item();
            $item->family_id = $request->tipologia_id;
            if(Category::find($request->categoria_id)){
                $item->category_id = $request->categoria_id;
            }
            $item->code = $code;
            $langs = config('app.available_translations');
            $fields = array();

            foreach ($langs as $lang) {
                $fields[$lang] = ['name' => $request->nome,'description' => $request->nome,'meta_title' => $request->nome,'meta_description' => $request->nome,'og_title' => $request->nome,'og_description' => $request->nome];
            }
            $item->fill($fields);
        }
        $item->deleted_at = null;
        $item->price = $request->prezzo_vendita;
        $item->discount = $request->sconto;
        $item->stock = $request->stock;
        $item->save();

        if($item->sizes()){
            ItemSize::where('item_id', $item->id)->delete();
        }

        foreach ($request->taglie as $taglia) {
            $itemSize = new ItemSize();
            $itemSize->item_id = $item->id;
            $itemSize->size_id = $taglia['taglia_id'];
            $itemSize->qty = $taglia['qty'];
            $itemSize->save();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function updatePrice(Request $request)
    {
        // if(!Auth::user()->can('delete event')){
        //     abort(503);
        // }
        foreach ($request->items as $item) {
            $item = Item::where('code',$item->codice_fornitore)->first();
            $item->price = $request->prezzo_vendita;
            $item->discount = $request->sconto;
            $item->stock = $request->stock;
            $item->save();

            $item->sizes()->detach();
            $item->sizes()->sync($request->taglie);
            $item->save();
        }


        return response()->json([
            'success' => true
        ]);
    }

    public function updateStock(Request $request, $code)
    {
        // if(!Auth::user()->can('delete event')){
        //     abort(503);
        // }

        $item = Item::where('code',$code)->first();
        $item->stock = $request->item->stock;
        $item->save();

        if($item->sizes()){
            ItemSize::where('item_id', $item->id)->delete();
        }

        foreach ($request->taglie as $taglia) {
            $itemSize = new ItemSize();
            $itemSize->item_id = $item->id;
            $itemSize->size_id = $taglia->taglia_id;
            $itemSize->qty = $taglia->qty;
            $itemSize->save();
        }
        $success = $item->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroy($code)
    {
        // if(!Auth::user()->can('delete event')){
        //     abort(503);
        // }

        $item = Item::where('code',$code)->first();
        $success = $item->delete();

        return response()->json([
            'success' => $success
        ]);
    }
}
