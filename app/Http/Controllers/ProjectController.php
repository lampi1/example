<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Models\Project;
use App\Daran\Models\ProjectCategory;
use App\Daran\Models\Gallery;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Daran\MetaTags\Facades\MetaTag;

class ProjectController extends Controller
{
    public function show($permalink)
    {
        $project = Project::with('category','project_components','project_components.images')->locale()->published()->where('slug','=',$permalink)->first();

        if (!$project && Auth::guard('admin')->check()) {
            $project = Project::locale()->where('slug','=',$permalink)->first();
        }
        if (!$project) {
            abort(404);
        }
        $related = Project::with('category','project_components','project_components.images')->locale()->published()->where('id','!=',$project->id)->inRandomOrder()->take(2)->get();


        MetaTag::createFromMetable($project);
        return view()->first(['front.project.show'], compact('project', 'related'));
    }

    public function index()
    {
        // $projects = Project::with('category')->locale()->published()->orderBy('published_at','desc')->get();
        $categories = ProjectCategory::locale()->get();

        $clients = Gallery::with('gallery_medias')->locale()->first();
        return view('front.project.list', compact('categories','clients'));
    }
}
