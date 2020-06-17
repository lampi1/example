<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Priority\HasPriority;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\EventCategoryUpdated;

class EventCategory extends Model implements HasMeta
{
    use HasSlug, HasPriority;

    protected $guarded = [];
    protected $appends = ['title'];
    public $timestamps = false;

    public function getTranslationsAttribute()
    {
        return EventCategory::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    protected $dispatchesEvents = [
        'updated' => EventCategoryUpdated::class,
    ];

    public function events() {
        return $this->hasMany('App\Daran\Models\Event');
    }

    public function active_events() {
        return $this->hasMany('App\Daran\Models\Event')->published()->where('date_start','>=',date('Y-m-d'));
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
