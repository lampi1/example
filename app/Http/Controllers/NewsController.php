<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Models\News;
use App\Daran\Models\NewsCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Daran\MetaTags\Facades\MetaTag;

class NewsController extends Controller
{
    public function show($permalink)
    {
        $news = News::with('category')->locale()->published()->where('slug','=',$permalink)->first();
        if (!$news && Auth::guard('admin')->check()) {
            $news = News::with('category')->locale()->where('slug','=',$permalink)->first();
        }
        if(!$news){
            abort(404);
        }

        $related = News::where('news_category_id','=',$news->news_category_id)->where('id','!=',$news->id)->locale()->published()->take(6)->get();

        MetaTag::createFromMetable($news);

        return view('front.news.show', compact('news','related'));
    }

    public function index(Request $request){
       if ($request->ajax()) {
           $news = News::locale()->published()->orderBy('published_at','desc')->paginate(6);
           $view = view('front.news.load', compact('news'))->render();
           return response()->json(['html'=>$view]);
       }else{
           $news = News::locale()->published()->orderBy('scheduled_at','desc')->paginate(6);
           $categories = NewsCategory::withCount('active_news')->locale()->get();
           return view('front.news.list', compact('news','categories'));
       }
    }

    public function category($permalink)
    {
        $category = NewsCategory::where('slug',$permalink)->firstOrFail();
        $news = News::locale()->published()->where('news_category_id',$category->id)->orderBy('published_at','desc')->get(6);
        $categories = NewsCategory::withCount('active_news')->locale()->get();

        MetaTag::createFromMetable($category);

        return view('front.news.category', compact('news','categories','category'));
    }

}
