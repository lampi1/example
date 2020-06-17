<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Models\News;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function getNews(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');
        $sort_array = explode('|',$sort);

        $qb = News::select('id','title','state','created_at', 'scheduled_at')->when($request->filled('lang'),function($q) use($request){
            return $q->where('locale',$request->lang);
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

        $news = News::findOrFail($id);
        if ($news->state != 'draft') {
            $news->state = 'draft';
        } else {
            $news->state = 'published';
            $news->published_at = new Carbon();
        }

        $success = $news->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete news')){
        //     abort(503);
        // }
        $news = News::findOrFail($id);
        $filename = $news->image;
        if ($news->delete()) {
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

    public function destroyAttachment($id)
    {
         // if(!Auth::user()->can('edit news')){
         //     abort(503);
         // }

         $attachment = NewsAttachment::findOrFail($id);
         $filename = $attachment->file;
         if ($attachment->delete()) {
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

          $news = News::findOrFail($id);
          $type = $request->get('type','standard');
          if($type == 'mobile'){
              $filename = $news->image_sm;
              $news->image_sm = null;
          }else{
              $filename = $news->image;
              $news->image = null;
          }

          if ($news->save()) {
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
