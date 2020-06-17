<?php

namespace App\Daran\Listeners;

use App\Daran\Events\EventUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateEventSlug
{
    public function __construct()
    {

    }

    public function handle(EventUpdated $event)
    {
        $old_slug = $event->event->getOriginal('slug');
        $current_slug = $event->event->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->event->locale, route('events.view',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->event->locale, route('events.view',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'Event '.$event->event->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','events.view')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->event->id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
