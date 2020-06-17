<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Cloner\Cloneable;

class Menu extends Model
{
    use Cloneable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $clone_exempt_attributes = [
        'locale_group'
    ];

    public function getTranslationsAttribute()
    {
        return Menu::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function admin()
    {
        return $this->belongsTo('App\Daran\Models\Admin');
    }

    public function menu_items()
    {
        return $this->hasMany('App\Daran\Models\MenuItem')->orderBy('priority')->orderBy('name');
    }

    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }

    public function rootMenuItems()
    {
        return $this->hasMany(MenuItem::class)->where('parent_id', null)->orderBy('parent_id')->orderBy('priority')->orderBy('name');
    }

    public function formatForAPI()
    {
        return [
            'id' => $this->id,
            'name' => $this->position,
            'locale' => $this->locale,
            'menuItems' => collect($this->rootMenuItems)->map(function ($item) {
                return $this->formatMenuItem($item);
            }),
        ];
    }
    public function formatMenuItem($menuItem)
    {
        return [
            'id' => $menuItem->id,
            'name' => $menuItem->name,
            'route' => $menuItem->route,
            'url' => $menuItem->url,
            'target' => $menuItem->target,
            'parameters' => $menuItem->parameters,
            'model_id' => $menuItem->model_id,
            'children' => empty($menuItem->children) ? [] : $menuItem->children->map(function ($item) {
                return $this->formatMenuItem($item);
            }),
        ];
    }
}
