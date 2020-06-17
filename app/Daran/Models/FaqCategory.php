<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Priority\HasPriority;

class FaqCategory extends Model
{
    use HasSlug, HasPriority;

    protected $guarded = [];

    public $timestamps = false;

    public function getTranslationsAttribute()
    {
        return FaqCategory::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function faqs() {
        return $this->hasMany('App\Daran\Models\Faq');
    }

    public function active_faqs() {
        return $this->hasMany('App\Daran\Models\Faq')->published();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
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
