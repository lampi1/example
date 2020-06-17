<?php

namespace App\Daran\Listeners;

use App\Daran\Events\PostUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\PostCategory;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdatePostSlug
{
    public function __construct()
    {

    }

    public function handle(PostUpdated $event)
    {
        $old_slug = $event->post->getOriginal('slug');
        $current_slug = $event->post->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_category = PostCategory::find($event->post->getOriginal('post_category_id'))->slug;
        $new_category = PostCategory::find($event->post->post_category_id)->slug;

        $old_url = LaravelLocalization::getLocalizedURL($event->post->locale, route('blogs.view',['permalink'=>$old_slug,'category'=>$old_category]));
        $new_url = LaravelLocalization::getLocalizedURL($event->post->locale, route('blogs.view',['permalink'=>$current_slug,'category'=>$new_category]));

        Redirection::create([
            'name'=>'Post '.$event->post->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','blogs.view')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->post->id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
