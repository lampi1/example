<?php

namespace App\Daran\Listeners;

use App\Daran\Events\EventCategoryUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateEventCategorySlug
{
    public function __construct()
    {

    }

    public function handle(EventCategoryUpdated $event)
    {
        $old_slug = $event->category->getOriginal('slug');
        $current_slug = $event->category->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('events.category',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('events.category',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'Event Category '.$event->category->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','events.category')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->category->id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
