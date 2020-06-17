<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Models\Post;
use App\Daran\Models\Item;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class PostController extends Controller
{
    public function getPosts(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');
        $sort_array = explode('|',$sort);

        $qb = Post::select('id','title','state','created_at')->when($request->filled('lang'),function($q) use($request){
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
        // if(!Auth::user()->can('publish post')){
        //     abort(503);
        // }

        $post = Post::findOrFail($id);
        if ($post->state != 'draft') {
            $post->state = 'draft';
        } else {
            $post->state = 'published';
            $post->published_at = new Carbon();
        }

        $success = $post->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete post')){
        //     abort(503);
        // }
        $post = Post::findOrFail($id);
        $filename = $post->image;
        if ($post->delete()) {
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
         // if(!Auth::user()->can('publish post')){
         //     abort(503);
         // }

         $attachment = PostAttachment::findOrFail($id);
         $post_id = $attachment->post_id;
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
          // if(!Auth::user()->can('edit post')){
          //     abort(503);
          // }

          $post = Post::findOrFail($id);
          $type = $request->get('type','standard');
          if($type == 'mobile'){
              $filename = $post->image_sm;
              $post->image_sm = null;
          }else{
              $filename = $post->image;
              $post->image = null;
          }

          if($post->save()) {
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


      public function getPost($id)
      {
          $item = Post::with('related','related.images')->findOrFail($id);
          return response()->json([
              'success' => true,
              'item' => $item
          ]);
      }

      public function removeRelated(Request $request, $id)
      {
          $post = Post::findOrFail($id);
          $post->related()->detach($request->related_id);
          return response()->json([
              'success' => true
          ]);
      }

      public function addRelated(Request $request, $id)
      {
          $post = Post::findOrFail($id);
          $new_item = Item::with('related','images','related.images')->findOrFail($request->related_id);
          $post->related()->attach($request->related_id);
          return response()->json([
              'success' => true,
              'item' => $new_item
          ]);
      }

}
