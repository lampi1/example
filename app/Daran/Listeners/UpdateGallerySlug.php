<?php

namespace App\Daran\Listeners;

use App\Daran\Events\GalleryUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateGallerySlug
{
    public function __construct()
    {

    }

    public function handle(GalleryUpdated $event)
    {
        $old_slug = $event->gallery->getOriginal('slug');
        $current_slug = $event->gallery->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->gallery->locale, route('galleries.view',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->gallery->locale, route('galleries.view',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'Gallery '.$event->gallery->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','galleries.view')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->gallery->id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
