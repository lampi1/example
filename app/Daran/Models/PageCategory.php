<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Priority\HasPriority;

class PageCategory extends Model
{
    use HasSlug;
    use HasPriority;

    protected $guarded = [];

    public $timestamps = false;

    public function getTranslationsAttribute()
    {
        return PageCategory::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function pages() {
        return $this->hasMany('App\Daran\Models\Page');
    }

    public function active_posts() {
        return $this->hasMany('App\Models\Post')->published()->locale();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Scope a query to only include news of the current lang.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }
}
