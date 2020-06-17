<?php

namespace App\Daran\Listeners;

use App\Daran\Events\CategoryUpdated;
use App\Daran\Models\Redirection;
use App\Daran\Models\FamilyTranslation;
use App\Daran\Models\Category;
use App\Daran\Models\MenuResource;
use App\Daran\Models\MenuItem;
use Illuminate\Queue\SerializesModels;
use LaravelLocalization;

class UpdateCategorySlug
{
    public function __construct()
    {

    }

    public function handle(CategoryUpdated $event)
    {
        // $old_slug = $event->category->getOriginal('slug');
        // $current_slug = $event->category->slug;
        //
        // if($old_slug == $current_slug){
        //     return;
        // }
        //
        // $old_family_id = Category::find($event->category->getOriginal('category_id'))->family_id;
        // $new_family_id = Category::find($event->category->category_id)->family_id;
        //
        // $old_family = FamilyTranslation::where('locale',$event->category->locale)->where('family_id',$old_family_id)->first()->slug;
        // $new_family = FamilyTranslation::where('locale',$event->category->locale)->where('family_id',$new_family_id)->first()->slug;
        //
        // $old_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('collection.view',['family'=>$old_family,'category'=>$old_slug]));
        // $new_url = LaravelLocalization::getLocalizedURL($event->category->locale, route('collection.view',['family'=>$new_family,'category'=>$current_slug,]));
        //
        // Redirection::create([
        //     'name'=>'Category '.$event->category->category_id,
        //     'code'=>'301',
        //     'from_uri'=>str_replace(config('app.url').'/','',$old_url),
        //     'to_uri'=>str_replace(config('app.url').'/','',$new_url)
        // ]);
        //
        // $mr = MenuResource::find(16);
        // if(!$mr){
        //     return;
        // }
        //
        // $mis = MenuItem::where('menu_resource_id',$mr->id)->where('model_id',$event->category->category_id)->get();
        // foreach($mis as $mi){
        //     $params = $mi->parameters;
        //     $params['family'] = $new_family;
        //     $params['category'] = $current_slug;
        //     $mi->parameters = $params;
        //     $mi->save();
        // }
    }
}
