<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Daran\Models\Project;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\ProjectComponent;
use App\Daran\Models\ProjectComponentImage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function getProjects(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');

        $qb = Project::select('id','title','state','created_at')->when($request->filled('lang'),function($q) use($request){
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
        // if(!Auth::user()->can('publish page')){
        //     abort(503);
        // }

        $page = Project::findOrFail($id);
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
        $page = Project::findOrFail($id);
        $filename = array();
        $filename[] = $page->image;
        $filename[] = $page->image_sm;
        $filename[] = $page->video_mp4;
        $filename[] = $page->video_ogv;
        $filename[] = $page->video_webm;

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

    public function deleteImage(Request $request, $id)
    {
          // if(!Auth::user()->can('edit page')){
          //     abort(503);
          // }
          $page = Project::findOrFail($id);
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

    public function deleteVideo(Request $request, $id)
    {
        $page = Project::findOrFail($id);
        $type = $request->get('type','video_mp4');
        if($type == 'video_ogv'){
            $filename = $page->video_ogv;
            $page->video_ogv = null;
        }elseif($type == 'video_webm'){
            $filename = $page->video_webm;
            $page->video_webm = null;
        }else{
            $filename = $page->video_mp4;
            $page->video_mp4 = null;
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

    public function getComponents(Request $request, $project_id)
    {
         $page = $request->get('page',0);
         $limit = $request->get('per_page',25);
         $sort = $request->get('sort','priority|asc');
         $sort_array = explode('|',$sort);

         $qb = ProjectComponent::select('id','name','image','priority')->where('project_id',$project_id);

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

    public function destroyComponent($id)
    {
        $component = ProjectComponent::findOrFail($id);
        $project_id = $component->project_id;

        $files = array();
        $files[] = $component->image;
        $files[] = $component->image_sm;

        if ($component->delete()) {
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

    public function reorderComponents(Request $request, $parent_id)
    {
        $id = trim($request->id);
        $new_index = trim($request->new_index);
        $old_index = trim($request->old_index);

        $component = ProjectComponent::find($id);
        $component->priority = $new_index;
        $component->save();

        if($old_index < $new_index){
            if(($new_index-$old_index) == 1){
                $components = ProjectComponent::where('project_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->get();
            }else{
                $components = ProjectComponent::where('project_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->get();
            }
        }else{
            if(($old_index-$new_index) == 1){
                $components = ProjectComponent::where('project_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->get();
            }else{
                $components = ProjectComponent::where('project_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->get();
            }
        }

        foreach ($components as $component) {
            $component->priority = ($old_index < $new_index) ? $component->priority-1 : $component->priority+1;
            $component->save();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function deleteComponentImage(Request $request,$id)
    {
        $component = ProjectComponent::findOrFail($id);
        $type = $request->get('type','standard');
        if($type == 'mobile'){
            $filename = $component->image_sm;
            $component->image_sm = null;
        }else{
            $filename = $component->image;
            $component->image = null;
        }

        if ($component->save()) {
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

    public function getComponent($id)
    {
        $item = ProjectComponent::with('images')->findOrFail($id);
        return response()->json([
            'success' => true,
            'item' => $item
        ]);
    }

    public function destroyImage(Request $request,$id)
    {
        $filename = array();
        $iimage = ProjectComponentImage::where('project_component_id',$id)->where('id',$request->image_id)->firstOrFail();
        $filename[] = $iimage->filename;
        $iimage->filename = null;
        $filename[] = $iimage->filename_sm;
        $iimage->filename_sm = null;

        if ($iimage->delete()) {
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

    public function addImage(Request $request,$id)
    {
         // if(!Auth::user()->can('edit item')){
         //     abort(503);
         // }

         $priority = DB::table('project_component_images')->where('project_component_id',$id)->max('priority');

         $image = new ProjectComponentImage();
         $image->project_component_id = $id;
         $image->priority = $priority ? ++$priority : 0;
         $image->save();

         $item = ProjectComponent::findOrFail($id);
         $file = $request->file('filepond');
         $extension = $file->getClientOriginalExtension() ?: 'png';
         $nome = Str::slug($item->title).'_'.uniqid().'.'.$extension;
         $img = $this->makeImage($file);
         $image->filename =  $this->saveImage($img,$nome, config('daran.images.breakpoints.standard'));
         $nome = Str::slug($item->title).'-mobile_'.uniqid().'.'.$extension;
         $image->filename_sm = $this->saveImage($img,$nome, config('daran.images.breakpoints.mobile'), true);

         if ($image->save()) {
             return response()->json([
                 'success' => true
             ]);
         } else {
             return response()->json([
                 'success' => false
             ]);
         }
    }

    public function reorderImages(Request $request,$id)
    {
        for($i=0;$i<count($request->order);$i++){
            $item = ProjectComponentImage::find($request->order[$i]['id']);
            $item->priority = $request->order[$i]['priority'];
            $item->save();
        }

        return response()->json([
            'success' => true
        ]);
    }
}
