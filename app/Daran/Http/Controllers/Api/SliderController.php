<?php

namespace App\Daran\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Daran\Models\Slider;
use App\Daran\Models\Slide;
use App\Daran\Http\Controllers\Controller;
use Carbon\Carbon;

class SliderController extends Controller
{
    public function getSliders(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','id|desc');
        $sort_array = explode('|',$sort);

        $qb = Slider::select('id','name','created_at')->when($request->filled('lang'),function($q) use($request){
            // return $q->where('locale',$request->lang);
        });

        $qb->when($request->filled('q'),function($q) use($request){
            return $q->where('name','like','%'.$request->get('q').'%');
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

    public function update(Request $request, $id)
    {
        // if(!Auth::user()->can('edit slider')){
        //     abort(503);
        // }

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $slider = Slider::findOrFail($id);
        $slider->name = $request->name;
        $success = $slider->save();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroy($id)
    {
        // if(!Auth::user()->can('delete slider')){
        //     abort(503);
        // }
        $slider = Slider::findOrFail($id);
        $slides = $slider->slides;

        $files = array();
        foreach($slides as $slide){
            $files[] = $slide->image;
            $files[] = $slide->image_lg;
            $files[] = $slide->image_md;
            $files[] = $slide->image_mobile;
            $files[] = $slide->image_sm;
            $files[] = $slide->image_xs;
        }
        if ($slider->delete()) {
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

    public function getSlides(Request $request, $slider_id)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',25);
        $sort = $request->get('sort','priority|asc');
        $sort_array = explode('|',$sort);

        $qb = Slide::select('id','title','type','image','priority')->where('slider_id',$slider_id);

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

    public function reorderSlides(Request $request, $parent_id)
    {
        $id = trim($request->id);
        $new_index = trim($request->new_index);
        $old_index = trim($request->old_index);

        $slide = Slide::find($id);
        $slide->priority = $new_index;
        $slide->save();

        if($old_index < $new_index){
            if(($new_index-$old_index) == 1){
                //$slides = Slide::where('slider_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('slides')->where('slider_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }else{
                //$slides = Slide::where('slider_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('slides')->where('slider_id',$parent_id)->where('priority', '<=',  $new_index)->where('priority', '>',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority - 1')]);
            }
        }else{
            if(($old_index-$new_index) == 1){
                //$slides = Slide::where('slider_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('slides')->where('slider_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<=',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }else{
                //$slides = Slide::where('slider_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->get();
                DB::table('slides')->where('slider_id',$parent_id)->where('priority', '>=',  $new_index)->where('priority', '<',  $old_index)->where('id', '!=', $request->id)->update(['priority'=>DB::raw('priority + 1')]);
            }
        }

        // foreach ($slides as $slide) {
        //     $slide->priority = ($old_index < $new_index) ? $slide->priority-1 : $slide->priority+1;
        //     $slide->save();
        // }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroySlide($id)
    {
        // if(!Auth::user()->can('delete slider')){
        //     abort(503);
        // }

        $slide = Slide::findOrFail($id);
        $slider_id = $slide->slider_id;

        $files = array();
        $files[] = $slide->image;
        $files[] = $slide->image_lg;
        $files[] = $slide->image_md;
        $files[] = $slide->image_mobile;
        $files[] = $slide->image_sm;
        $files[] = $slide->image_xs;

        if ($slide->delete()) {
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
         // if(!Auth::user()->can('edit slide')){
         //     abort(503);
         // }

         $slide = Slide::findOrFail($id);
         $type = $request->get('type','standard');
         if($type == 'mobile'){
             $filename = $slide->image_sm;
             $slide->image_sm = null;
         }else{
             $filename = $slide->image;
             $slide->image = null;
         }

         if ($slide->save()) {
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
