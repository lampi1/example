<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Models\Event;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class EventController extends Controller
{
    public function getEvents(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');
        $sort_array = explode('|',$sort);

        $qb = Event::select('id','title','state','created_at')->when($request->filled('lang'),function($q) use($request){
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

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete event')){
        //     abort(503);
        // }
        $event = Event::findOrFail($id);
        $filename = $event->image;
        if ($event->delete()) {
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

    public function changeState(int $id)
    {
        // if(!Auth::user()->can('publish event')){
        //     abort(503);
        // }

        $event = Event::findOrFail($id);
        if ($event->state != 'draft') {
            $event->state = 'draft';
        } else {
            $event->state = 'published';
            $event->published_at = new Carbon();
        }

        $success = $event->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroyAttachment($id)
    {
         // if(!Auth::user()->can('edit event')){
         //     abort(503);
         // }

         $attachment = EventAttachment::findOrFail($id);
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
          // if(!Auth::user()->can('edit event')){
          //     abort(503);
          // }

          $event = Event::findOrFail($id);
          $type = $request->get('type','standard');
          if($type == 'mobile'){
              $filename = $event->image_sm;
              $event->image_sm = null;
          }else{
              $filename = $event->image;
              $event->image = null;
          }

          if ($event->save()) {
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
