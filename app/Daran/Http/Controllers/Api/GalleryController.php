<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Models\Gallery;
use App\Daran\Models\GalleryMedia;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class GalleryController extends Controller
{
    public function getGalleries(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');
        $sort_array = explode('|',$sort);

        $qb = Gallery::select('id','title','state','created_at')->when($request->filled('lang'),function($q) use($request){
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
        // if(!Auth::user()->can('publish gallery')){
        //     abort(503);
        // }

        $gallery = Gallery::findOrFail($id);
        if ($gallery->state != 'draft') {
            $gallery->state = 'draft';
        } else {
            $gallery->state = 'published';
            $gallery->published_at = new Carbon();
        }

        $success = $gallery->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete gallery')){
        //     abort(503);
        // }

        $gallery = Gallery::findOrFail($id);
        $files = array();
        $files[] = $gallery->image;
        foreach($gallery->gallery_medias as $media_file){
            $files[] = $media_file->image;
            $files[] = $media_file->image_lg;
            $files[] = $media_file->image_md;
            $files[] = $media_file->image_mobile;
            $files[] = $media_file->image_sm;
            $files[] = $media_file->image_xs;
        }

        if ($gallery->delete()) {
            $this->deleteFiles($files);

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
         // if(!Auth::user()->can('edit gallery')){
         //     abort(503);
         // }

         $gallery = Gallery::findOrFail($id);
         $type = $request->get('type','standard');
         if($type == 'mobile'){
             $filename = $gallery->image_sm;
             $gallery->image_sm = null;
         }else{
             $filename = $gallery->image;
             $gallery->image = null;
         }

         if ($gallery->save()) {
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

    public function getMedias(Request $request, $gallery_id)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','priority|asc');
        $sort_array = explode('|',$sort);

        $qb = GalleryMedia::select('id','title','type','image','priority')->where('gallery_id',$gallery_id);

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

    public function reorderMedias(Request $request, $parent_id)
    {
        $id = trim($request->id);
        $new_index = trim($request->new_index);
        $old_index = trim($request->old_index);

        $gm = GalleryMedia::find($id);
        $gm->priority = $new_index;
        $gm->save();

        if($old_index < $new_index){
            if(($new_index-$old_index) == 1){
                //$gms = GalleryMedia::where('gallery_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('gallery_medias')->where('gallery_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }else{
                //$gms = GalleryMedia::where('gallery_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('gallery_medias')->where('gallery_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }
        }else{
            if(($old_index-$new_index) == 1){
                //$gms = GalleryMedia::where('gallery_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('gallery_medias')->where('gallery_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }else{
                //$gms = GalleryMedia::where('gallery_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('gallery_medias')->where('gallery_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }
        }

        // foreach ($gms as $gms) {
        //     $gms->priority = ($old_index < $new_index) ? $gms->priority-1 : $gms->priority+1;
        //     $gms->save();
        // }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroyMedia($id)
    {
        // if(!Auth::user()->can('delete gallery')){
        //     abort(503);
        // }

        $gm = GalleryMedia::findOrFail($id);
        $gallery_id = $gm->gallery_id;

        $files = array();
        $files[] = $gm->image;
        $files[] = $gm->image_lg;
        $files[] = $gm->image_md;
        $files[] = $gm->image_mobile;
        $files[] = $gm->image_sm;
        $files[] = $gm->image_xs;

        if ($gm->delete()) {
            $this->deleteFiles($files);

            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function deleteMediaImage(Request $request,$id)
    {
         // if(!Auth::user()->can('edit gallery')){
         //     abort(503);
         // }

         $media = GalleryMedia::findOrFail($id);
         $type = $request->get('type','standard');
         if($type == 'mobile'){
             $filename = $media->image_sm;
             $media->image_sm = null;
         }else{
             $filename = $media->image;
             $media->image = null;
         }

         if ($media->save()) {
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
