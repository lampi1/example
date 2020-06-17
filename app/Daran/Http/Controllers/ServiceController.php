<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Service;
use App\Daran\Models\ServiceCategory;
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
use App\Daran\Http\Requests\ServiceRequest;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read news')){
            abort(503);
        }

        return view('daran::services.index');
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

        $service = new Service();
        $service->search = 1;
        $service->seo = 1;
        $service->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $categories = ServiceCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $sliders = Slider::orderBy('name','asc')->get();
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

        return view('daran::services.create', compact('service','categories','locales','locale_group', 'vue_tags', 'vue_all_tags','sliders'));
    }

    public function store(ServiceRequest $request)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = Service::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0){
                return Redirect::back()->withInput()->with('error', trans('daran::messagenews_translation_exists'));
            }
        }

        $service = new Service($request->except('files','image','image_sm','attachment_title','attachment_file','tags_string','content'));
        $service->admin_id = Auth::user()->id;
        $service->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $service->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($service->state == 'public') {
            $service->published_at = new Carbon();
        }

        if($request->filled('content')){
            $service->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $service->locale.'-'.Str::slug($service->title).'_'.uniqid().'.'.$extension;
            $service->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $service->locale.'-mobile-'.Str::slug($service->title).'_'.uniqid().'.'.$extension;
            $service->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $service->locale.'-mobile-'.Str::slug($service->title).'_'.uniqid().'.'.$extension;
            $service->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        $service->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $service->syncTags($tags);
        }

        if ($service->id) {
            return Redirect::route('admin.services.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Request $request, Service $service)
    {
        if(!Auth::user()->can('edit news')){
            abort(503);
        }

        $page_tags = $service->tags_string;
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

        $categories = ServiceCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $sliders = Slider::orderBy('name','asc')->get();
        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        return view('daran::services.edit', compact('service', 'categories', 'locales', 'vue_tags', 'vue_all_tags','sliders'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        if(!Auth::user()->can('edit news')){
            abort(503);
        }
        $filename = array();
        $num = Service::where('locale','=',$request->locale)->where('locale_group','=',$service->locale_group)->where('id','<>',$service->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::messagenews_translation_exists'));
        }

        if ($service->state == 'draft' && $request->state == 'public') {
            $service->published_at = new Carbon();
        }

        $service->update($request->except('content','files','image','image_sm','attachment_title','attachment_file','tags_string','slug','content'));
        $service->admin_id = Auth::user()->id;
        $service->slug = Str::slug($request->slug);

        if($request->filled('content')){
            $service->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $filename[] = $service->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $service->locale.'-'.Str::slug($service->title).'_'.uniqid().'.'.$extension;
            $service->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $service->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $service->locale.'-mobile-'.Str::slug($service->title).'_'.uniqid().'.'.$extension;
            $service->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if (!$request->has('seo')){
            $service->seo = 0;
        }

        if (!$request->has('search')){
            $service->search = 0;
        }

        $service->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $service->syncTags($tags);
        }

        if ($service->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.services.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }


    public function clone(Request $request, int $id)
    {
        if(!Auth::user()->can('create news')){
            abort(503);
        }

        $service = Service::findOrFail($id);
        $clone = $service->duplicate();
        $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        if ($clone->save()) {
            return Redirect::route('admin.services.edit', ['service' => $clone->id])->with('success', trans('daran::common.duplication_success'));
        } else {
            return Redirect::route('admin.services.index')->with('error', trans('daran::common.duplication_fail'));
        }
    }
}
