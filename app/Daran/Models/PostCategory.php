<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Priority\HasPriority;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\PostCategoryUpdated;

class PostCategory extends Model implements HasMeta
{
    use HasSlug, HasPriority;

    protected $guarded = [];
    protected $appends = ['title'];
    public $timestamps = false;

    protected $dispatchesEvents = [
        'updated' => PostCategoryUpdated::class,
    ];

    public function getTranslationsAttribute()
    {
        return PostCategory::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function posts() {
        return $this->hasMany('App\Daran\Models\Post');
    }

    public function active_posts() {
        return $this->hasMany('App\Daran\Models\Post')->published();
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

    public function scopePublished($query)
    {
        return $query;
    }
    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function getMetaTitle() : string
    {
        return $this->meta_title ?? $this->name;
    }

    public function getOgTitle() : string
    {
        return $this->og_title ?? $this->getMetaTitle();
    }

    public function getMetaDescription() : string
    {
        return $this->meta_description ?? $this->name;
    }

    public function getOgDescription() : string
    {
        return $this->og_description ?? $this->getMetaDescription();
    }

    public function getOgImage() : string
    {
        //return $this->image ? config('app.url')."/".$this->image : '';
        return '';
    }

    public function getTranslations() : ?array
    {
        return $this->translations ? $this->translations->pluck('locale')->all() : null;
    }

    public function isSeoIndexable() : bool
    {
        return true;
    }
}
