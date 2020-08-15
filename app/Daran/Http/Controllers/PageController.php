<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Page;
use App\Daran\Models\PageCategory;
use App\Daran\Models\PageTemplate;
use App\Daran\Models\PageAttachment;
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
use App\Daran\Http\Requests\PageRequest;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can('read page')){
            abort(503);
        }

        return view('daran::pages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!Auth::user()->can('create page')){
            abort(503);
        }

        $page = new Page();
        $page->search = 1;
        $page->seo = 1;
        $page->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $fatherPages = Page::orderBy('title', 'ASC')->get();
        $pageCategories = PageCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $sliders = Slider::orderBy('name', 'ASC')->get();
        $pageTemplates = PageTemplate::orderBy('name','asc')->get();

        $all_tags = Tag::get();
        $vue_all_tags = array();
        foreach ($all_tags as $tag_object) {
            $vue_all_tags[]['text'] = $tag_object->name;
        }
        $vue_all_tags = json_encode($vue_all_tags);
        $vue_all_tags = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$vue_all_tags);
        $vue_tags = json_encode(array());

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::pages.create', compact('page', 'pageCategories', 'sliders', 'pageTemplates', 'fatherPages', 'locales', 'locale_group', 'vue_tags', 'vue_all_tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        if(!Auth::user()->can('create page')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = Page::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0){
                return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
            }
        }

        $page = new Page($request->except('files','image','image_sm','tags','attachment_title','attachment_file','tags_string','content'));
        $page->user_id = Auth::user()->id;
        $page->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $page->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($page->state == 'published') {
            $page->published_at = new Carbon();
        }

        if($request->filled('content')){
            $page->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $page->locale.'-'.Str::slug($page->title).'_'.uniqid().'.'.$extension;
            $page->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $page->locale.'-mobile-'.Str::slug($page->title).'_'.uniqid().'.'.$extension;
            $page->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $page->locale.'-mobile-'.Str::slug($page->title).'_'.uniqid().'.'.$extension;
            $page->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        $page->save();

        $allegati = array();
        $attachment_names = $request['attachment_title'];
        $attachment_files = $request['attachment_file'];
        if($attachment_files){
            $count = 0;
            foreach($attachment_files as $key => $value){
                $page_attachment = new PageAttachment();
                if($attachment_names[$key] == null || $attachment_names[$key] == ''){
                    $this->validate($request, [
                        'attachment_title[]' => 'required|max:255',
                    ]);
                }
                $page_attachment->title = $attachment_names[$key];
                if ($value->isValid()) {
                    $page_attachment->file = $this->saveFile($value, $count.'-'.$page_attachment->title);
                }
                $allegati[] = $page_attachment;
                $count++;
            }
            $page->page_attachments()->saveMany($allegati);
        }

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $page->syncTags($tags);
        }

        if ($page->id) {
            return Redirect::route('admin.pages.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Page $page)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $page_tags = $page->tags_string;
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

        $fatherPages = Page::where('id','!=',$page->id)->orderBy('title', 'ASC')->get();
        $pageCategories = PageCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $pageTemplates = PageTemplate::orderBy('name','asc')->get();

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }
        $sliders = Slider::orderBy('name', 'ASC')->get();

        return view('daran::pages.edit', compact('page', 'pageCategories', 'sliders', 'pageTemplates', 'fatherPages', 'locales', 'vue_tags', 'vue_all_tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, Page $page)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $num = Page::where('locale','=',$request->locale)->where('locale_group','=',$page->locale_group)->where('id','<>',$page->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
        }

        if ($page->state == 'draft' && $request->state == 'published') {
            $page->published_at = new Carbon();
        }

        $page->update($request->except('files','image','image_sm','tags','attachment_title','attachment_file','tags_string','content'));
        $page->user_id = Auth::user()->id;

        if($request->filled('content')){
            $page->content = $this->saveContentImage($request->get('content'));
        }

        $filename = array();
        if ($request->hasFile('image')) {
            $filename[] = $page->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $page->locale.'-'.Str::slug($page->title).'_'.uniqid().'.'.$extension;
            $page->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $page->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $page->locale.'-mobile-'.Str::slug($page->title).'_'.uniqid().'.'.$extension;
            $page->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if (!$request->has('seo')){
            $page->seo = 0;
        }

        if (!$request->has('search')){
            $page->search = 0;
        }

        $page->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $page->syncTags($tags);
        }

        $allegati = array();
        $attachment_names = $request['attachment_title'];
        $attachment_files = $request['attachment_file'];
        if($attachment_files){
            $count = 0;
            foreach($attachment_files as $key => $value){
                $page_attachment = new PageAttachment();
                if($attachment_names[$key] == null || $attachment_names[$key] == ''){
                    $this->validate($request, [
                        'attachment_title[]' => 'required|max:255',
                    ]);
                }
                $page_attachment->title = $attachment_names[$key];
                if ($value->isValid()) {
                    $page_attachment->file = $this->saveFile($value, $count.'-'.$page_attachment->title);
                }
                $allegati[] = $page_attachment;
                $count++;
            }
            $page->page_attachments()->saveMany($allegati);
        }


        if ($page->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.pages.index')->with('success', trans('daran::message.success.update'));
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
         if(!Auth::user()->can('create page')){
             abort(503);
         }

         $page = Page::findOrFail($id);
         $clone = $page->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('admin.pages.edit', ['page' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.pages.index')->with('error', trans('daran::message.error.clone'));
         }
     }
}
