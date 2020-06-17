<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\News;
use App\Daran\Models\NewsCategory;
use App\Daran\Models\Slider;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Spatie\Tags\Tag;
use App\Daran\Http\Requests\NewsRequest;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can('read news')){
            abort(503);
        }

        return view('daran::news.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        $news = new News();
        $news->search = 1;
        $news->seo = 1;
        $news->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $newsCategories = NewsCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $all_tags = Tag::get();
        $vue_all_tags = array();
        foreach ($all_tags as $tag_object) {
            $vue_all_tags[]['text'] = $tag_object->name;
        }
        $vue_all_tags = json_encode($vue_all_tags);
        $vue_all_tags = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$vue_all_tags);
        $vue_tags = json_encode(array());

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::news.create', compact('news','newsCategories','locales','locale_group', 'vue_tags', 'vue_all_tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = News::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0){
                return Redirect::back()->withInput()->with('error', trans('daran::messagenews_translation_exists'));
            }
        }

        $news = new News($request->except('files','image','image_sm','attachment_title','attachment_file','tags_string','content'));
        $news->admin_id = Auth::user()->id;
        $news->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $news->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($news->state == 'published') {
            $news->published_at = new Carbon();
        }

        if($request->filled('content')){
            $news->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $news->locale.'-'.Str::slug($news->title).'_'.uniqid().'.'.$extension;
            $news->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $news->locale.'-mobile-'.Str::slug($news->title).'_'.uniqid().'.'.$extension;
            $news->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $news->locale.'-mobile-'.Str::slug($news->title).'_'.uniqid().'.'.$extension;
            $news->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        $news->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $news->syncTags($tags);
        }

        $allegati = array();
        $attachment_names = $request['attachment_title'];
        $attachment_files = $request['attachment_file'];
        $count = 0;
        if($attachment_files){
            foreach($attachment_files as $key => $value){
                $news_attachment = new NewsAttachment();
                if($attachment_names[$key] == null || $attachment_names[$key] == ''){
                    $this->validate($request, [
                        'attachment_title[]' => 'required|max:255',
                    ]);
                }
                $news_attachment->title = $attachment_names[$key];
                if ($value->isValid()) {
                    $news_attachment->file = $this->saveFile($value, $count.'-'.$news_attachment->title);
                }
                $allegati[] = $news_attachment;
                $count++;
            }
            $news->news_attachments()->saveMany($allegati);
        }

        if ($news->id) {
            return Redirect::route('admin.news.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, News $news)
    {
        if(!Auth::user()->can('edit news')){
            abort(503);
        }

        $page_tags = $news->tags_string;
        $vue_tags = array();
        foreach ($page_tags as $tag_object) {
            $vue_tags[]['text'] = $tag_object;
        }
        $vue_tags = json_encode($vue_tags);
        $vue_tags = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$vue_tags);

        $all_tags = Tag::get();
        $vue_all_tags = array();
        foreach ($all_tags as $tag_object) {
            $vue_all_tags[]['text'] = $tag_object->name;
        }
        $vue_all_tags = json_encode($vue_all_tags);
        $vue_all_tags = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$vue_all_tags);

        $newsCategories = NewsCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        return view('daran::news.edit', compact('news', 'newsCategories', 'locales', 'vue_tags', 'vue_all_tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\NewsRequest  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(NewsRequest $request, News $news)
    {
        if(!Auth::user()->can('edit faq')){
            abort(503);
        }
        $filename = array();
        $num = News::where('locale','=',$request->locale)->where('locale_group','=',$news->locale_group)->where('id','<>',$news->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::messagenews_translation_exists'));
        }

        if ($news->state == 'draft' && $request->state == 'public') {
            $news->published_at = new Carbon();
        }

        $news->update($request->except('content','files','image','image_sm','attachment_title','attachment_file','tags_string','slug','content'));
        $news->admin_id = Auth::user()->id;
        $news->slug = Str::slug($request->slug);

        if($request->filled('content')){
            $news->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $filename[] = $news->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $news->locale.'-'.Str::slug($news->title).'_'.uniqid().'.'.$extension;
            $news->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $news->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $news->locale.'-mobile-'.Str::slug($news->title).'_'.uniqid().'.'.$extension;
            $news->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if (!$request->has('seo')){
            $news->seo = 0;
        }

        if (!$request->has('search')){
            $news->search = 0;
        }

        $news->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $news->syncTags($tags);
        }

        $allegati = array();
        $attachment_names = $request['attachment_title'];
        $attachment_files = $request['attachment_file'];
        $count = 0;
        if($attachment_files){
            foreach($attachment_files as $key => $value){
                $news_attachment = new NewsAttachment();
                if($attachment_names[$key] == null || $attachment_names[$key] == ''){
                    $this->validate($request, [
                        'attachment_title[]' => 'required|max:255',
                    ]);
                }
                $news_attachment->title = $attachment_names[$key];
                if ($value->isValid()) {
                    $news_attachment->file = $this->saveFile($value, $count.'-'.$news_attachment->title);
                }
                $allegati[] = $news_attachment;
                $count++;
            }
            $news->news_attachments()->saveMany($allegati);
        }


        if ($news->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.news.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }


    /**
     * Clone the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clone(Request $request, int $id)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        $news = News::findOrFail($id);
        $clone = $news->duplicate();
        $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        if ($clone->save()) {
            return Redirect::route('admin.news.edit', ['news' => $clone->id])->with('success', trans('daran::common.duplication_success'));
        } else {
            return Redirect::route('admin.news.index')->with('error', trans('daran::common.duplication_fail'));
        }
    }
}
