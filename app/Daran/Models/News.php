<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Cloner\Cloneable;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\NewsUpdated;

class News extends Model implements HasMeta
{
    use HasTags, HasSlug, Cloneable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
        'ended_at',
        'scheduled_at'
    ];

    /*protected $cloneable_relations = [
        'category',
        'author'
    ];*/

    protected $clone_exempt_attributes = [
        'locale_group', 'slug', 'state'
    ];

    protected $dispatchesEvents = [
        'updated' => NewsUpdated::class,
    ];

    public function setPublishedAtAttribute($published_at)
    {
        $this->attributes['published_at'] = trim($published_at) == '' ? null : trim($published_at);
    }
    public function setEndedAtAttribute($ended_at)
    {
        $this->attributes['ended_at'] = $ended_at ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $ended_at) : null;
    }
    public function setScheduledAtAttribute($scheduled_at)
    {
        $this->attributes['scheduled_at'] = $scheduled_at ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $scheduled_at) : null;
    }

    public function getTranslationsAttribute()
    {
        return News::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function getTagsStringAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }

    public function admin() {
        return $this->belongsTo('App\Daran\Models\Admin','admin_id');
    }

    public function category() {
        return $this->belongsTo('App\Daran\Models\NewsCategory','news_category_id');
    }

    public function news_attachments() {
        return $this->hasMany('App\Daran\Models\NewsAttachment');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['title','locale'])
            ->saveSlugsTo('slug');
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
     * Scope a query to only include news of the current lang.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }

    /**
     * Scope a query to only include currently published news.
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
