<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Event;
use App\Daran\Models\EventCategory;
use App\Daran\Models\EventAttachment;
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
use App\Daran\Http\Requests\EventRequest;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read event')){
            abort(503);
        }

        return view('daran::events.index');
    }

    public function create(Request $request)
    {
        if(!Auth::user()->can('create event')){
            abort(503);
        }

        $event = new Event();
        $event->search = 1;
        $event->seo = 1;
        $event->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());


        $eventCategories = EventCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();
        // $provinces = Province::orderBy('name','asc')->get();
        // $clients = Client::locale()->published()->orderBy('title','asc')->get();

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

        return view('daran::events.create', compact('event', 'eventCategories', 'locales', 'locale_group', 'vue_tags', 'vue_all_tags'));
    }

    public function store(EventRequest $request)
    {
        if(!Auth::user()->can('create event')){
            abort(503);
        }

        if ($request->filled('locale_group')) {
            $num = Event::where('locale','=',$request->locale)->where('locale_group','=',$request->locale_group)->count();
            if ($num > 0){
                return Redirect::back()->withInput()->with('error', trans('daran::message.error.translation_exist'));
            }
        }

        $event = new Event($request->except('image','image_sm','files','attachment_title','attachment_file','tags_string','content'));
        $event->user_id = Auth::user()->id;
        $event->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $event->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($event->state == 'public') {
            $event->published_at = new Carbon();
        }

        if($request->filled('content')){
            $event->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $event->locale.'-'.Str::slug($event->title).'_'.uniqid().'.'.$extension;
            $event->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $event->locale.'-mobile-'.Str::slug($event->title).'_'.uniqid().'.'.$extension;
            $event->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }elseif ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $event->locale.'-mobile-'.Str::slug($event->title).'_'.uniqid().'.'.$extension;
            $event->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'), true);
        }

        if ($event->save()) {
            if($request->filled('tags_string')){
                $tags = array();
                foreach(json_decode($request->tags_string,true) as $tag){
                    $tags[] = $tag['text'];
                }
                $evet->syncTags($tags);
            }

            $allegati = array();
            $attachment_names = $request['attachment_title'];
            $attachment_files = $request['attachment_file'];
            if($request->filled('attachment_file')){
                $count = 0;
                foreach($attachment_files as $key => $value){
                    $post_attachment = new EventAttachment();
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
                $event->event_attachments()->saveMany($allegati);
            }

            return Redirect::route('admin.events.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Event $event)
    {
        if(!Auth::user()->can('edit event')){
            abort(503);
        }

        $page_tags = $event->tags_string;
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

        $eventCategories = EventCategory::where('locale','=',session('working_lang', Lang::getLocale()))->orderBy('name','asc')->get();

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }

        return view('daran::events.edit', compact('event', 'eventCategories', 'locales', 'vue_tags', 'vue_all_tags'));
    }

    public function update(EventRequest $request, Event $event)
    {
        if(!Auth::user()->can('edit event')){
            abort(503);
        }

        $filename = array();

        if ($event->state == 'draft' && $request->state == 'public') {
            $event->published_at = new Carbon();
        }
        $event->update($request->except('image','image_sm','files','attachment_title','attachment_file','tags_string','slug','content'));

        $event->user_id = Auth::user()->id;

        if($request->filled('content')){
            $event->content = $this->saveContentImage($request->get('content'));
        }

        if ($request->hasFile('image')) {
            $filename[] = $event->image;
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $event->locale.'-'.Str::slug($event->title).'_'.uniqid().'.'.$extension;
            $event->image = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.standard'));
        }
        if ($request->hasFile('image_sm')) {
            $filename[] = $event->image_sm;
            $file = $request->file('image_sm');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = $event->locale.'-mobile-'.Str::slug($event->title).'_'.uniqid().'.'.$extension;
            $event->image_sm = $this->saveImage($img,$nome_originale, config('daran.images.breakpoints.mobile'));
        }

        if (!$request->has('seo')){
            $event->seo = 0;
        }

        if (!$request->has('search')){
            $event->search = 0;
        }

        if($request->filled('tags_string')){
            $tags = array();
            foreach(json_decode($request->tags_string,true) as $tag){
                $tags[] = $tag['text'];
            }
            $event->syncTags($tags);
        }

        $allegati = array();
        $attachment_files = $request->file('attachment_file');
        if($attachment_files){
            $attachment_names = $request['attachment_title'];
            $count = 0;
            foreach($attachment_files as $key => $value){
                $post_attachment = new EventAttachment();

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
            $event->event_attachments()->saveMany($allegati);
        }

        if ($event->save()) {
            $this->deleteFiles($filename);
            return Redirect::route('events.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create event')){
             abort(503);
         }

         $event = Event::findOrFail($id);
         $clone = $event->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('admin.events.edit', ['event' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.events.index')->with('error', trans('daran::message.error.clone'));
         }
    }

}
