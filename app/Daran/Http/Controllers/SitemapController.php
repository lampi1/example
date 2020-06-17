<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Models\Page;
use App\Daran\Models\Post;
use App\Daran\Models\PostCategory;
use App\Daran\Models\Event;
use App\Daran\Models\EventCategory;
use App\Daran\Models\News;
use App\Daran\Models\NewsCategory;
use App\Daran\Models\Faq;
use App\Daran\Models\Gallery;
use App\Daran\Models\Family;
use App\Daran\Models\Category;
use App\Daran\Models\Item;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::published()->where('seo',1)->orderBy('updated_at', 'desc')->get();
        $postcategories = PostCategory::orderBy('priority', 'asc')->get();
        $pages = Page::published()->where('seo',1)->orderBy('updated_at', 'desc')->get();
        $events = Event::published()->where('seo',1)->orderBy('updated_at', 'desc')->get();
        $eventcategories = EventCategory::orderBy('priority', 'asc')->get();
        $news = News::published()->where('seo',1)->orderBy('updated_at', 'desc')->get();
        $newscategories = NewsCategory::orderBy('priority', 'asc')->get();
        $faqs = Faq::published()->where('seo',1)->orderBy('updated_at', 'desc')->get();
        $galleries = Gallery::published()->where('seo',1)->orderBy('updated_at', 'desc')->get();

        if(config('daran.ecommerce.enable')){
            $families = Family::get();
            $categories = Category::get();
            $items = Item::where('published','1')->get();
        }else{
            $families = collect(array());
            $categories = collect(array());
            $items = collect(array());
        }

        $locales = config('app.available_translations');

        return response()->view('daran::sitemap.index', compact('locales','posts','postcategories','pages','events','eventcategories','news','newscategories','faqs','galleries','families','categories','items'))->header('Content-Type', 'text/xml');
    }
}
