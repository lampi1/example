<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Models\LandingPage;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function getPages(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = LandingPage::select('id','title','state','created_at')->when($request->filled('lang'),function($q) use($request){
            return $q->where('locale',$request->lang);
        });

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('title','like','%'.$request->get('q').'%');
        });

        $sort_array = explode('|',$sort);
        if(count($sort_array) == 2){
            $qb->orderBy($sort_array[0],$sort_array[1]);
        }


        if ($limit) {
            $paginator = $qb->paginate($limit);
            //dd($paginator);
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
        // if(!Auth::user()->can('publish landing_page')){
        //     abort(503);
        // }

        $page = LandingPage::findOrFail($id);
        if ($page->state != 'draft') {
            $page->state = 'draft';
        } else {
            $page->state = 'published';
            $page->published_at = new Carbon();
        }

        $success = $page->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete landing_page')){
        //     abort(503);
        // }
        $page = LandingPage::findOrFail($id);
        $filename = array();
        $filename[] = $page->image;
        $filename[] = $page->image_sm;
        $success = $page->delete();
        if($success){
            $this->deleteFiles($filename);
        }
        return response()->json([
            'success' => $success
        ]);
    }

     public function deleteImage(Request $request, $id)
     {
          // if(!Auth::user()->can('edit landing_page')){
          //     abort(503);
          // }
          $page = LandingPage::findOrFail($id);
          $type = $request->get('type','standard');
          if($type == 'mobile'){
              $filename = $page->image_sm;
              $page->image_sm = null;
          }else{
              $filename = $page->image;
              $page->image = null;
          }

          if ($page->save()) {
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
