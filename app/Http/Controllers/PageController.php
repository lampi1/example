<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Models\Page;
use App\Daran\Models\Project;
use App\Daran\Models\Gallery;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Daran\MetaTags\Facades\MetaTag;

class PageController extends Controller
{
    public function show($permalink)
    {
        $page = Page::with('slider')->locale()->published()->where('slug','=',$permalink)->first();
        if (!$page && Auth::guard('admin')->check()) {
            $page = Page::with('slider')->locale()->where('slug','=',$permalink)->first();
        }
        if (!$page) {
            abort(404);
        }

        if ($page->template->template == 'sales-promotion-co-marketing') {
            $projects = Project::locale()->published()->where('project_category_id', 1)->inRandomOrder()->take(2)->get();
        } elseif ($page->template->template == 'events') {
            $projects = Project::locale()->published()->where('project_category_id', 3)->inRandomOrder()->take(2)->get();
        } elseif ($page->template->template == 'packaging') {
            $projects = Project::locale()->published()->where('project_category_id', 4)->inRandomOrder()->take(2)->get();
        } elseif ($page->template->template == 'incentive') {
            $projects = Project::locale()->published()->where('project_category_id', 5)->inRandomOrder()->take(2)->get();
        } else {
            $projects = Project::locale()->published()->inRandomOrder()->take(2)->get();
        }


        MetaTag::createFromMetable($page);

        $clients = Gallery::with('gallery_medias')->locale()->first();




        return view()->first(['front.page.'.$page->template->template,'front.page.show'], compact('page','clients','projects'));
    }

}
