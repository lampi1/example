<?php

namespace App\Daran\Listeners;

use App\Daran\Events\FamilyUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\CategoryTranslation;
use App\Daran\Models\Category;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateFamilySlug
{
    public function __construct()
    {

    }

    public function handle(FamilyUpdated $event)
    {
        // $old_slug = $event->family->getOriginal('slug');
        // $current_slug = $event->family->slug;
        //
        // if($old_slug == $current_slug){
        //     return;
        // }
        //
        // $old_url = LaravelLocalization::getLocalizedURL($event->family->locale, route('collection.view',['family'=>$old_slug,'category'=>null]));
        // $new_url = LaravelLocalization::getLocalizedURL($event->family->locale, route('collection.view',['family'=>$current_slug,'category'=>null]));
        //
        // Redirection::create([
        //     'name'=>'Family '.$event->family->family_id,
        //     'code'=>'301',
        //     'from_uri'=>str_replace(config('app.url').'/','',$old_url),
        //     'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        // ]);
        //
        // $categories = CategoryTranslation::whereHas('category',function($q) use($event){
        //     $q->where('family_id',$event->family->family_id);
        // })->where('locale',$event->family->locale)->get();
        //
        // foreach($categories as $cat){
        //     $old_url = LaravelLocalization::getLocalizedURL($event->family->locale, route('collection.view',['family'=>$old_slug,'category'=>$cat->slug]));
        //     $new_url = LaravelLocalization::getLocalizedURL($event->family->locale, route('collection.view',['family'=>$current_slug,'category'=>$cat->slug]));
        //
        //     Redirection::create([
        //         'name'=>'Category '.$cat->id,
        //         'code'=>'301',
        //         'from_uri'=>str_replace(config('app.url').'/','',$old_url),
        //         'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        //     ]);
        // }
        //
        // $mr = MenuResource::find(15);
        // if($mr){
        //     $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->family->family_id)->get();
        //     foreach($mis as $mi){
        //         $params = $mi->parameters;
        //         $params['family'] = $current_slug;
        //         $mi->parameters = $params;
        //         $mi->save();
        //     }
        // }
        //
        // $mr = MenuResource::find(16);
        // if(!$mr){
        //     return;
        // }
        //
        // $mis = MenuItem::where('menu_resource_id',$mr->id)->get();
        // foreach($mis as $mi){
        //     $category = Category::find($mis->model_id);
        //     if($category->family_id == $event->family->family_id){
        //         $params = $mi->parameters;
        //         $params['family'] = $current_slug;
        //         $mi->parameters = $params;
        //         $mi->save();
        //     }
        //
        // }
    }
}
