<?php

namespace App\Daran\Listeners;

use App\Daran\Events\ItemUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateItemSlug
{
    public function __construct()
    {

    }

    public function handle(ItemUpdated $event)
    {
        $old_slug = $event->item->getOriginal('slug');
        $current_slug = $event->item->slug;

        if($old_slug == $current_slug){
            return;
        }

        $old_url = LaravelLocalization::getLocalizedURL($event->item->locale, route('products.view',['permalink'=>$old_slug]));
        $new_url = LaravelLocalization::getLocalizedURL($event->item->locale, route('products.view',['permalink'=>$current_slug]));

        Redirection::create([
            'name'=>'Item '.$event->item->item_id,
            'code'=>'301',
            'from_uri'=>str_replace(config('app.url').'/','',$old_url),
            'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        ]);

        $mr = MenuResource::where('route','products.view')->first();
        if(!$mr){
            return;
        }

        $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->item->item_id)->get();
        foreach($mis as $mi){
            $params = $mi->parameters;
            $params['permalink'] = $current_slug;
            $mi->parameters = $params;
            $mi->save();
        }
    }
}
