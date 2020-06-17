<?php

namespace App\Daran\Listeners;

use App\Daran\Events\NewsUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateNewsSlug
{
    public function __construct()
    {

    }

    public function handle(NewsUpdated $event)
    {
        $old_slug = $event->news->getOriginal('slug');
        $current_slug = $event->news->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->news->locale, route('news.view',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->news->locale, route('news.view',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'News '.$event->news->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','news.view')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->news->id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
