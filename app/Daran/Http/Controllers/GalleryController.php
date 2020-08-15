<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Gallery;
use App\Daran\Models\GalleryMedia;
use App\Daran\Models\GalleryCategory;
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
use App\Daran\Http\Requests\GalleryRequest;
use App\Daran\Http\Requests\GalleryMediaRequest;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read gallery')){
            abort(503);
        }

        return view('daran::galleries.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create gallery')){
            abort(503);
        }
        $gallery = new Gallery();
        $gallery->search = 1;
        $gallery->seo = 1;
        $gallery->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

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
        $categories = GalleryCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        return view('daran::galleries.create', compact('gallery', 'locales', 'locale_group', 'vue_tags', 'vue_all_tags','categories'));
    }

    public function store(GalleryRequest $request)
    {
        if(!Auth::user()->can('create gallery')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = Gallery::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0){
                return Redirect::back()->withInput()->with('error', trans('daran::message.gallery_translation_exists'));
            }
        }

        $gallery = new Gallery($request->except('image','image_sm','tags','tags_string','content'));
        $gallery->user_id = Auth::user()->id;
        $gallery->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $gallery->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($gallery->state == 'public') {
            $gallery->published_at = new Carbon();
        }

        if($request->filled('content')){
            $gallery->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $gallery->locale.'-'.Str::slug($gallery->title).'_'.uniqid().'.'.$extension;
            $gallery->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $gallery->locale.'-mobile-'.Str::slug($gallery->title).'_'.uniqid().'.'.$extension;
            $gallery->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $gallery->locale.'-mobile-'.Str::slug($gallery->title).'_'.uniqid().'.'.$extension;
            $gallery->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }


        if ($gallery->save()) {
            return Redirect::route('admin.galleries.edit',['gallery' => $gallery])->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Request $request, Gallery $gallery)
    {
        if(!Auth::user()->can('edit gallery')){
            abort(503);
        }
        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        $gallery_tags = $gallery->tags_string;
        $vue_tags = array();
        foreach ($gallery_tags as $tag_object) {
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
        $categories = GalleryCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();

        return view('daran::galleries.edit', compact('gallery', 'locales', 'vue_tags', 'vue_all_tags','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryRequest $request, Gallery $gallery)
    {
        if(!Auth::user()->can('edit gallery')){
            abort(503);
        }

        $num = Gallery::where('locale','=',$request->locale)->where('locale_group','=',$gallery->locale_group)->where('id','<>',$gallery->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
        }

        $filename = array();

        if ($gallery->state == 'draft' && $request->state == 'public') {
            $gallery->published_at = new Carbon();
        }
        $gallery->update($request->except('files','image','image_sm','slug','tags','tags_string','content'));
        $gallery->user_id = Auth::user()->id;
        $gallery->slug = Str::slug($request->slug);

        if($request->filled('content')){
            $gallery->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $filename[] = $gallery->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $gallery->locale.'-'.Str::slug($gallery->title).'_'.uniqid().'.'.$extension;
            $gallery->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $gallery->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $gallery->locale.'-mobile-'.Str::slug($gallery->title).'_'.uniqid().'.'.$extension;
            $gallery->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if (!$request->has('seo')){
            $gallery->seo = 0;
        }

        if (!$request->has('search')){
            $gallery->search = 0;
        }

        if ($gallery->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.galleries.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create gallery')){
             abort(503);
         }

         $gallery = Gallery::findOrFail($id);
         $clone = $gallery->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('admin.galleries.edit', ['gallery' => $clone])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.galleries.index')->with('error', trans('daran::message.error.clone'));
         }
    }

    public function cloneMedia(Request $request, int $id)
    {
         if(!Auth::user()->can('create gallery')){
             abort(503);
         }

         $gm = GalleryMedia::findOrFail($id);
         $clone = $gm->duplicate();
         if ($clone->save()) {
             return Redirect::route('admin.galleries.edit', ['gallery' => $clone->gallery])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.galleries.edit', ['gallery' => $gm->gallery])->with('error', trans('daran::message.error.clone'));
         }
    }

    public function addMedia($id, $type)
    {
        if(!Auth::user()->can('edit gallery')){
            abort(503);
        }

        $media = new GalleryMedia();
        $media->gallery_id = $id;
        $media->type = $type;

        return view('daran::galleries.add-media', compact('media'));
    }

    public function storeMedia(GalleryMediaRequest $request)
    {
        if(!Auth::user()->can('edit gallery')){
            abort(503);
        }

        $media = new GalleryMedia($request->except('image','image_xs','video'));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = Str::slug($media->title).'_'.uniqid().'.'.$extension;
            $media->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($media->title).'_'.uniqid().'.'.$extension;
            $media->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($media->title).'_'.uniqid().'.'.$extension;
            $media->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = Str::slug($media->title).'_'.uniqid().'.'.$extension;
            $media->video = $this->saveFile($file,$nome_originale);
        }

        if($media->save()){
            return Redirect::route('admin.galleries.edit',['gallery' => $media->gallery])->with('success', trans('daran::message.success.create'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function editMedia($id)
    {
        if(!Auth::user()->can('edit gallery')){
            abort(503);
        }
        $media = GalleryMedia::findOrFail($id);

        return view('daran::galleries.edit-media', compact('media'));
    }

    public function updateMedia(GalleryMediaRequest $request, $id)
    {
        if(!Auth::user()->can('edit gallery')){
            abort(503);
        }
        $filename = array();
        $media = GalleryMedia::findOrFail($id);
        $media->update($request->except('image','image_sm','video'));

        if ($request->hasFile('image')) {
            $filename[] = $media->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = Str::slug($media->title).'_'.uniqid().'.'.$extension;
            $media->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $media->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'mobile-'.Str::slug($media->title).'_'.uniqid().'.'.$extension;
            $media->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }
        if ($request->hasFile('video')) {
            $filename[] = $media->video;
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $nome_originale = Str::slug($media->title).'_'.uniqid().'.'.$extension;
            $media->video = $this->saveFile($file,$nome_originale);
        }

        if($media->save()){
            return Redirect::route('admin.galleries.edit',['gallery' => $media->gallery])->with('success', trans('daran::message.success.update'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id media
     * @return \Illuminate\Http\Response
     */
    public function destroyMedia($id)
    {
        if(!Auth::user()->can('delete gallery')){
            abort(503);
        }

        $media = GalleryMedia::findOrFail($id);
        $gallery = $media->gallery;
        $files = array();
        $files[] = $media->thumb;
        $files[] = $media->thumb_mobile;
        $files[] = $media->image;
        $files[] = $media->image_lg;
        $files[] = $media->image_md;
        $files[] = $media->image_mobile;
        $files[] = $media->image_sm;
        $files[] = $media->image_xs;

        if ($media->delete()) {
            $this->deleteFiles($files);
            return Redirect::route('admin.galleries.edit',['gallery' => $gallery])->with('success', trans('daran::message.success.delete'));
        } else {
            return Redirect::route('admin.galleries.edit',['gallery' => $gallery])->with('error', trans('daran::message.error.delete'));
        }
    }

    /**
    * Reorder the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function reorder(Request $request)
   {

       for($i=0;$i<count($request->order);$i++){
           GalleryMedia::find($request->order[$i])->update(['priority'=>$i]);
       }

       return response()->json([
           'status' => 'ok'
       ]);
   }
}
