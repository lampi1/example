<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Post;
use App\Daran\Models\PostCategory;
use App\Daran\Models\PostAttachment;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Daran\Http\Requests\PostRequest;
use Spatie\Tags\Tag;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read post')){
            abort(503);
        }

        return view('daran::posts.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create post')){
            abort(503);
        }
        $post = new Post();
        $post->search = 1;
        $post->seo = 1;
        $post->featured = 0;
        $post->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        $postCategories = PostCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();

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

        return view('daran::posts.create', compact('post','postCategories','locales','locale_group', 'vue_tags', 'vue_all_tags'));

    }

    public function store(PostRequest $request)
    {
        if(!Auth::user()->can('create post')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
           $num = Post::where('locale',$request->locale)->where('locale_group',$request->locale_group)->count();
           if ($num > 0){
               return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
           }
       }

        $post = new Post($request->except('files','image','image_sm','attachment_title','attachment_file','author_image','tags_string','content'));
        $post->user_id = Auth::user()->id;
        $post->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $post->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        if ($post->state == 'published') {
            $post->published_at = new Carbon();
        }

        $pc = PostCategory::find($post->post_category_id);
        if($pc->locale != $post->locale){
            $pc = PostCategory::where('locale_group',$pc->locale_group)->first();
            $post->post_category_id = $pc->id;
        }

        if($request->filled('content')){
            $post->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $post->locale.'-'.Str::slug($post->title).'_'.uniqid().'.'.$extension;
            $post->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $post->locale.'-mobile-'.Str::slug($post->title).'_'.uniqid().'.'.$extension;
            $post->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $post->locale.'-mobile-'.Str::slug($post->title).'_'.uniqid().'.'.$extension;
            $post->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        $post->save();

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $post->syncTags($tags);
        }

        $allegati = array();
        $attachment_names = $request['attachment_title'];
        $attachment_files = $request['attachment_file'];
        if($request->filled('attachment_file')){
            $count = 0;
            foreach($attachment_files as $key => $value){
                $post_attachment = new PostAttachment();
                if($attachment_names[$key] == null || $attachment_names[$key] == ''){
                    $this->validate($request, [
                        'attachment_title[]' => 'required|max:255',
                    ]);
                }
                $post_attachment->title = $attachment_names[$key];
                if ($value->isValid()) {
                    $post_attachment->file = $this->saveFile($value, $count.'-'.$post_attachment->title);
                }
                $allegati[] = $post_attachment;
                $count++;
            }
            $post->post_attachments()->saveMany($allegati);
        }

        if ($post->id) {
            return Redirect::route('admin.posts.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function edit(Request $request, Post $post)
    {
        if(!Auth::user()->can('edit post')){
            abort(503);
        }

        $page_tags = $post->tags_string;
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

        $postCategories = PostCategory::where('locale','=',$post->locale)->orderBy('name','asc')->get();
        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        return view('daran::posts.edit', compact('postCategories', 'post', 'locales', 'vue_tags', 'vue_all_tags'));
    }

    public function update(PostRequest $request, Post $post)
    {
        if(!Auth::user()->can('edit post')){
            abort(503);
        }
        $filename = array();
        $num = Post::where('locale','=',$request->locale)->where('locale_group','=',$post->locale_group)->where('id','<>',$post->id)->count();
        if ($num > 0){
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
        }

        if ($post->state == 'draft' && $request->state == 'published') {
            $post->published_at = new Carbon();
        }
        $post->update($request->except('files','image','image_sm','attachment_title','attachment_file','tags_string','slug','content'));
        $post->user_id = Auth::user()->id;
        $post->slug = Str::slug($request->slug);

        $pc = PostCategory::find($post->post_category_id);
        if($pc->locale != $post->locale){
            $pc = PostCategory::where('locale_group',$pc->locale_group)->first();
            $post->post_category_id = $pc->id;
        }

        if($request->filled('content')){
            $post->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $filename[] = $post->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $post->locale.'-'.Str::slug($post->title).'_'.uniqid().'.'.$extension;
            $post->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $post->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $post->locale.'-mobile-'.Str::slug($post->title).'_'.uniqid().'.'.$extension;
            $post->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if (!$request->has('seo')){
            $post->seo = 0;
        }
        if (!$request->has('search')){
            $post->search = 0;
        }
        // if (!$request->has('featured')){
        //     $post->featured = 0;
        // }

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $post->syncTags($tags);
        }

        $post->save();

        $allegati = array();
        $attachment_files = $request->file('attachment_file');
        if($attachment_files){
            $attachment_names = $request['attachment_title'];
            $count = 0;
            foreach($attachment_files as $key => $value){
                $post_attachment = new PostAttachment();

                if($attachment_names[$key] == null || $attachment_names[$key] == ''){
                    $this->validate($request, [
                        'attachment_title[]' => 'required|max:255',
                    ]);
                }
                $post_attachment->title = $attachment_names[$key];
                if ($value->isValid()) {
                    $post_attachment->file = $this->saveFile($value, $count.'-'.$post_attachment->title);
                }
                $allegati[] = $post_attachment;
                $count++;
            }
            $post->post_attachments()->saveMany($allegati);
        }
        if ($post->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('admin.posts.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }


    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create post')){
             abort(503);
         }

         $post = Post::findOrFail($id);
         $clone = $post->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('admin.posts.edit', ['post' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.posts.index')->with('error', trans('daran::message.error.clone'));
         }
    }

    public function editRelated(Request $request, $id)
    {
        // if(!Auth::user()->can('edit post')){
        //     abort(503);
        // }

        return view('daran::posts.related',compact('id'));
    }

}
