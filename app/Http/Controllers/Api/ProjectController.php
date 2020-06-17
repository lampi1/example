<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Daran\Models\Project;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page',0);
        $limit = $request->get('per_page',10);

        $paginator = Project::with('category')->locale()->published()->when($request->filled('category_id'),function($q) use($request){
            return $q->where('project_category_id',$request->category_id);
        })->orderBy('published_at','desc')->paginate($limit);

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

        return response()->json([
            'links' => $links,
            'projects' => $items,
        ]);
    }
}
