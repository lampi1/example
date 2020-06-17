<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Project;
use App\Daran\Models\ProjectComponent;
use App\Daran\Models\ProjectCategory;
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
use App\Daran\Http\Requests\ProjectRequest;
use Illuminate\Support\Str;

class ProjectController extends Controller
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

        return view('daran::projects.index');
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

        $project = new Project();
        $project->search = 1;
        $project->seo = 1;
        $project->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

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
        $categories = ProjectCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        return view('daran::projects.create', compact('project','categories', 'locales', 'locale_group', 'vue_tags', 'vue_all_tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        if(!Auth::user()->can('create page')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = Project::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0){
                return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
            }
        }

        $project = new Project($request->except('files','image','image_sm','image_video','tags','tags_string','content','video_mp4','video_ogv','video_webm'));
        $project->admin_id = Auth::user()->id;
        $project->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $project->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($project->state == 'published') {
            $project->published_at = new Carbon();
        }

        if($request->filled('content')){
            $project->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $project->locale.'-mobile-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $project->locale.'-mobile-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if ($request->hasFile('image_video')) {
            $file = $request->file('image_video');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->image_video = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }

        if ($request->hasFile('video_mp4')) {
            $file = $request->file('video_mp4');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->video_mp4 = $this->saveFile($file,$nome_originale);
        }

        if ($request->hasFile('video_ogv')) {
            $file = $request->file('video_ogv');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->video_ogv = $this->saveFile($file,$nome_originale);
        }

        if ($request->hasFile('video_webm')) {
            $file = $request->file('video_webm');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->video_webm = $this->saveFile($file,$nome_originale);
        }

        $project->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $project->syncTags($tags);
        }

        if ($project->id) {
            return Redirect::route('admin.projects.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Request $request, Project $project)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $page_tags = $project->tags_string;
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


        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }
        $categories = ProjectCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        return view('daran::projects.edit', compact('project','categories', 'locales', 'vue_tags', 'vue_all_tags'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $num = Project::where('locale','=',$request->locale)->where('locale_group','=',$project->locale_group)->where('id','<>',$project->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
        }

        if ($project->state == 'draft' && $request->state == 'published') {
            $project->published_at = new Carbon();
        }

        $project->update($request->except('image_video','image','image_sm','video_mp4','video_ogv','video_webm','tags','tags_string','content'));
        $project->admin_id = Auth::user()->id;

        if($request->filled('content')){
            $project->content = $this->saveContentImage($request->get('content'));
        }

        $filename = array();
        if ($request->hasFile('image')) {
            $filename[] = $project->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $project->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $project->locale.'-mobile-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if ($request->hasFile('image_video')) {
            $file = $request->file('image_video');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->image_video = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }

        if ($request->hasFile('video_mp4')) {
            $filename[] = $project->video_mp4;
            $file = $request->file('video_mp4');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->video_mp4 = $this->saveFile($file,$nome_originale);
        }

        if ($request->hasFile('video_ogv')) {
            $filename[] = $project->video_ogv;
            $file = $request->file('video_ogv');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->video_ogv = $this->saveFile($file,$nome_originale);
        }

        if ($request->hasFile('video_webm')) {
            $filename[] = $project->video_webm;
            $file = $request->file('video_webm');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = $project->locale.'-'.Str::slug($project->title).'_'.uniqid().'.'.$extension;
            $project->video_webm = $this->saveFile($file,$nome_originale);
        }

        if (!$request->has('seo')){
            $project->seo = 0;
        }

        if (!$request->has('search')){
            $project->search = 0;
        }

        $project->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $project->syncTags($tags);
        }


        if ($project->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.projects.index')->with('success', trans('daran::message.success.update'));
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

         $page = Project::findOrFail($id);
         $clone = $page->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('admin.projects.edit', ['project' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.projects.index')->with('error', trans('daran::message.error.clone'));
         }
     }

    public function addComponent($id)
    {
        $component = new ProjectComponent();
        $component->project_id = $id;

        return view('daran::projects.add-component', compact('component'));
    }

    public function storeComponent(Request $request)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }
        $component = new ProjectComponent($request->except('image','image_xs'));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = Str::slug($component->name).'_'.uniqid().'.'.$extension;
            $component->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($component->name).'_'.uniqid().'.'.$extension;
            $component->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($component->name).'_'.uniqid().'.'.$extension;
            $component->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if($component->save()){
            return Redirect::route('admin.projects.edit',['project' => $component->project])->with('success', trans('daran::message.success.create'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function cloneComponent(Request $request, int $id)
    {
         if(!Auth::user()->can('create page')){
             abort(503);
         }

         $cp = ProjectComponent::findOrFail($id);
         $clone = $cp->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.components.edit', ['id' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.projects.edit', ['project' => $cp->project])->with('error', trans('daran::message.error.clone'));
         }
    }

    public function editComponent($id)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $component = ProjectComponent::findOrFail($id);

        return view('daran::projects.edit-component', compact('component'));
    }

    public function updateComponent(Request $request, $id)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }

        $filename = array();

        $component = ProjectComponent::findOrFail($id);
        $component->update($request->except('image','image_sm'));

        if ($request->hasFile('image')) {
            $filename[] = $component->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = Str::slug($component->name).'_'.uniqid().'.'.$extension;
            $component->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $component->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($component->name).'_'.uniqid().'.'.$extension;
            $component->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if ($component->save()) {
            return Redirect::route('admin.projects.edit',['project'=>$component->project])->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function editImages(Request $request, $id)
    {
        if(!Auth::user()->can('edit page')){
            abort(503);
        }
        $this->getPath();
        return view('daran::projects.images',compact('id'));
    }
}
