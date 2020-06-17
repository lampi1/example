<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Menu;
use App\Daran\Models\MenuItem;
use App\Daran\Models\MenuResource;
use Carbon\Carbon;

class MenuBuilderController extends Controller
{
    public function getMenuItems($menu_id)
    {
        $menu = Menu::findOrFail($menu_id)->first();
        $menu->formatForApi();
        return response()->json([
            'success' => true,
            'items' => $menu,
        ]);
    }

    public function getResources(Request $request)
    {
        $model = $request->get('type','App\Daran\Models\Page');
        $items = $model::published()->get();

        return response()->json([
            'success' => true,
            'items' => $items,
        ]);
    }

    public function saveItems(Request $request,$id)
    {
        $menu = Menu::findOrFail($id);
        $items = $request->get('items');
        $i = 1;
        foreach ($items as $item) {
            $this->saveMenuItem($i,$item);
            $i++;
        }

        return response()->json([
            'success' => true
        ]);
    }

    private function saveMenuItem($priority, $item, $parentId = null)
    {
        $menuItem = MenuItem::find($item['id']);
        $menuItem->priority = $priority;
        $menuItem->parent_id = $parentId;
        $menuItem->save();
        $this->checkChildren($item);
    }

    private function checkChildren($item)
    {
        if (count($item['children']) > 0) {
            $i = 1;
            foreach ($item['children'] as $child) {
                $this->saveMenuItem($i, $child, $item['id']);
                $i++;
            }
        }
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('manage menu')){
        //     abort(503);
        // }

        $mi = MenuItem::findOrFail($id);
        $success = $mi->delete();

        return response()->json([
            'success' => $success
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'menu_id' => 'required|exists:menus,id',
            'menu_resource.id' => 'required|exists:menu_resources,id',
            'target' => 'required',
        ];

        $validation = Validator::make($request->all(),$rules);

        if($validation->fails()){
            return $validation->errors();
        }

        $mr = MenuResource::findOrFail($request->menu_resource['id']);
        $tmp = ($request->id > 0) ? MenuItem::findOrFail($request->id) : new MenuItem();
        $tmp->name = $request->name;
        $tmp->menu_id = $request->menu_id;
        $tmp->menu_resource_id = $mr->id;
        $tmp->name = $request->name;
        $tmp->target = $request->target;
        $tmp->parent_id = $request->parent_id;
        $tmp->model_id = $request->model_id;
        $tmp->route_name = $mr->route;
        $tmp->priority = MenuItem::max('id') + 1;

        if($tmp->model_id){
            $parameters = array();
            $model = $mr->model;
            $mod_obg = $model::findOrFail($tmp->model_id);
            foreach($mr->params as $params){
                foreach($params as $key=>$value){
                    $value_array = explode('.',$value);
                    if(count($value_array) == 1){
                        $parameters[$key] = $mod_obg->$value;
                    }else{
                        $partial_obj = $mod_obg;
                        foreach($value_array as $field){
                            $partial_obj = $partial_obj->$field;
                        }
                        $parameters[$key] = $partial_obj;
                    }
                }
            }
            $tmp->parameters = $parameters;
        }

        $tmp->save();

        return response()->json([
            'result' => 'ok',
        ]);
    }

}
