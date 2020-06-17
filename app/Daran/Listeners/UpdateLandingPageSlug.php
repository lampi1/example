<?php

namespace App\Daran\Listeners;

use App\Daran\Events\LandingPageUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateLandingPageSlug
{
    public function __construct()
    {

    }

    public function handle(LandingPageUpdated $event)
    {
        $old_slug = $event->page->getOriginal('slug');
        $current_slug = $event->page->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->page->locale, route('landings.view',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->page->locale, route('landings.view',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'LandingPage '.$event->page->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','landings.view')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->page->id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
