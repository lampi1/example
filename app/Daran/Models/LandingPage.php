<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Cloner\Cloneable;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\LandingPageUpdated;

class LandingPage extends Model implements HasMeta
{
    use HasSlug, Cloneable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at'
    ];

    protected $clone_exempt_attributes = [
        'locale_group', 'slug', 'state'
    ];

    protected $dispatchesEvents = [
        'updated' => LandingPageUpdated::class,
    ];

    public function setPublishedAtAttribute($published_at)
    {
        $this->attributes['published_at'] = trim($published_at) == '' ? null : trim($published_at);
    }

    public function getTranslationsAttribute()
    {
        return LandingPage::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function admin()
    {
        return $this->belongsTo('App\Daran\Models\Admin');
    }

    public function template()
    {
        return $this->belongsTo('App\Daran\Models\LandingPageTemplate');
    }

    public function slider() {
        return $this->belongsTo('App\Daran\Models\Slider');
    }

    public function form() {
        return $this->belongsTo('App\Daran\Models\Form');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['title','locale'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Sets the attributes that can not be cloned before saving to db
     *
     * @return array
     */
    public function onCloning($src) {
		$this->state = 'draft';
	}

    /**
     * Scope a query to only include page of the current lang.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }

    /**
     * Scope a query to only include currently published page.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('state','!=','draft')->whereNotNull('published_at');
    }

    public function getMetaTitle() : string
    {
        return $this->meta_title ?? $this->title;
    }

    public function getOgTitle() : string
    {
        return $this->og_title ?? $this->getMetaTitle();
    }

    public function getMetaDescription() : string
    {
        return $this->meta_description ?? $this->abstract;
    }

    public function getOgDescription() : string
    {
        return $this->og_description ?? $this->getMetaDescription();
    }

    public function getOgImage() : string
    {
        return $this->image ? config('app.url')."/".$this->image : '';
    }

    public function getTranslations() : ?array
    {
        return $this->translations ? $this->translations->pluck('locale')->all() : null;
    }

    public function isSeoIndexable() : bool
    {
        return $this->seo;
    }
}
