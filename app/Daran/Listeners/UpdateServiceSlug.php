<?php

namespace App\Daran\Listeners;

use App\Daran\Events\ServiceUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateServiceSlug
{
    public function __construct()
    {

    }

    public function handle(ServiceUpdated $event)
    {
        $old_slug = $event->service->getOriginal('slug');
        $current_slug = $event->service->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->service->locale, route('services.view',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->service->locale, route('services.view',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'Service '.$event->service->id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','service.view')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->service->id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
