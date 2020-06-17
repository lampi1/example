<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Models\Service;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function getServices(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');
        $sort_array = explode('|',$sort);

        $qb = Service::select('services.id as id','title','state','created_at', 'service_categories.name as category')
        ->join('service_categories','services.service_category_id','=','service_categories.id')
        ->when($request->filled('lang'),function($q) use($request){
            return $q->where('services.locale',$request->lang);
        });

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('title','like','%'.$request->get('q').'%');
        });

        if(count($sort_array) == 2){
            $qb->orderBy($sort_array[0],$sort_array[1]);
        }

        if ($limit) {
            $paginator = $qb->paginate($limit);
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
            $items = $paginator->items();//CounterpartResource::collection($qb->paginate($limit));
        } else {
            $links = array();
            $items = $qb->get();
        }

        return response()->json([
            'links' => $links,
            'data' => $items,
        ]);
    }

    public function changeState(int $id)
    {
        // if(!Auth::user()->can('publish news')){
        //     abort(503);
        // }

        $item = Service::findOrFail($id);
        if ($item->state != 'draft') {
            $item->state = 'draft';
        } else {
            $item->state = 'published';
            $item->published_at = new Carbon();
        }

        $success = $item->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete news')){
        //     abort(503);
        // }
        $item = Service::findOrFail($id);
        $filename = $item->image;
        if ($item->delete()) {
            $this->deleteFiles($filename);
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function deleteImage(Request $request,$id)
    {
        // if(!Auth::user()->can('edit news')){
        //     abort(503);
        // }

        $item = Service::findOrFail($id);
        $type = $request->get('type','standard');
        if($type == 'mobile'){
            $filename = $item->image_sm;
            $item->image_sm = null;
        }else{
            $filename = $item->image;
            $item->image = null;
        }

        if ($item->save()) {
            $this->deleteFiles($filename);
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }
}
