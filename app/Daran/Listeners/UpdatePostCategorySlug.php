<?php

namespace App\Daran\Listeners;

use App\Daran\Events\PostCategoryUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\Post;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdatePostCategorySlug
{
    public function __construct()
    {

    }

    public function handle(PostCategoryUpdated $event)
    {
        $old_slug = $event->category->getOriginal('slug');
        $current_slug = $event->category->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('blogs.category',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('blogs.category',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'Post Category '.$event->category->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $posts = Post::where('post_category_id',$event->category->id)->published()->get();
        foreach($posts as $post){
            $old_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('blogs.view',['category'=>$old_slug,'permalink'=>$post->slug]));
            $new_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('blogs.view',['category'=>$current_slug,'permalink'=>$post->slug]));

            Redirection::create([
                'name'=>'Post '.$post->id,
                'code'=>'301',
                'from_uri'=>str_replace(config('app.url').'/','',$old_url),
                'to_uri'=>str_replace(config('app.url').'/','',$new_url)
            ]);
        }

        $mr = MenuResource::where('route','blogs.category')->first();
        if($mr){
            $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->category->id)->get();
            foreach($mis as $mi){
                $params = $mi->parameters;
                $params['permalink'] = $current_slug;
                $mi->parameters = $params;
                $mi->save();
            }
        }

        $mr = MenuResource::where('route','blogs.view')->first();
        if($mr){
            $mis = MenuItem::where('menu_resource_id',$mr->id)->get();
            foreach($mis as $mi){
                if($mi->parameters['category'] == $old_slug){
                    $params = $mi->parameters;
                    $params['category'] = $current_slug;
                    $mi->parameters = $params;
                    $mi->save();
                }

            }
        }
    }
}
