<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Daran\Models\Page;
use App\Daran\Models\PageAttachment;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class PageController extends Controller
{
    public function getPages(Request $request)
    {
        //$type = $request->get('type','all');
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        //$filters = $request->get('filter',array());
        $sort = $request->get('sort','id|desc');
        //$sortable_fields = ['NDG','codice_fiscale','name'];

        $qb = Page::select('id','title','state','created_at')->when($request->filled('lang'),function($q) use($request){
            return $q->where('locale',$request->lang);
        });

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('title','like','%'.$request->get('q').'%');
        });

        // if($type == 'legal'){
        //     $qb->whereNotNull('legal_person_id');
        // }
        // if($type == 'natural'){
        //     $qb->whereNotNull('natural_person_id');
        // }
        //
        // if (count($filters) > 0) {
        //     if (array_key_exists('name', $filters) && $filters['name'] != '') {
        //         $qb->where(function($query) use ($filters){
        //             $query->whereHas('natural_person', function($q) use ($filters){
        //                 $q->where('surname','LIKE','%'.$filters['name'].'%');
        //             })->orWhereHas('legal_person', function($q) use ($filters){
        //                 $q->where('business_name','LIKE','%'.$filters['name'].'%');
        //             });
        //         });
        //     }
        //
        //     if (array_key_exists('fiscale', $filters) && $filters['fiscale'] != '') {
        //         $qb->where(function($query) use ($filters){
        //             $query->whereHas('natural_person', function($q) use ($filters){
        //                 $q->where('codice_fiscale','LIKE','%'.$filters['fiscale'].'%');
        //             })->orWhereHas('legal_person', function($q) use ($filters){
        //                 $q->where('codice_fiscale','LIKE','%'.$filters['fiscale'].'%')->orWhere('partita_iva','LIKE','%'.$filters['fiscale'].'%');
        //             });
        //         });
        //     }
        //
        //     if (array_key_exists('piva', $filters) && $filters['piva'] != '') {
        //         $qb->where(function($query) use ($filters){
        //             $query->whereHas('natural_person', function($q) use ($filters){
        //                 $q->whereIn('codice_fiscale',explode(',', $filters['piva']));
        //             })->orWhereHas('legal_person', function($q) use ($filters){
        //                 $q->whereIn('partita_iva',explode(',', $filters['piva']));
        //             });
        //         });
        //     }
        //
        //     if (array_key_exists('ndg', $filters) && $filters['ndg'] != '') {
        //         $qb->whereIn('uuid',explode(',', $filters['ndg']));
        //     }
        // }

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
            $items = CounterpartResource::collection($qb->get());
        }

        return response()->json([
            'links' => $links,
            'data' => $items,
        ]);
    }

    public function changeState(int $id)
    {
        // if(!Auth::user()->can('publish page')){
        //     abort(503);
        // }

        $page = Page::findOrFail($id);
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
        // if(!Auth::user()->can('delete page')){
        //     abort(503);
        // }
        $page = Page::findOrFail($id);
        $filename = array();
        $filename[] = $page->image;
        $filename[] = $page->image_sm;
        if ($page->delete()) {
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
         // if(!Auth::user()->can('edit page')){
         //     abort(503);
         // }

         $attachment = PageAttachment::findOrFail($id);
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

     public function deleteImage(Request $request, $id)
     {
          // if(!Auth::user()->can('edit page')){
          //     abort(503);
          // }
          $page = Page::findOrFail($id);
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
