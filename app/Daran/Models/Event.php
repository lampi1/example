<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Cloner\Cloneable;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\EventUpdated;

class Event extends Model implements HasMeta
{
    use HasTags, HasSlug, Cloneable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
        'date_start',
        'date_end',
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
        'updated' => EventUpdated::class,
    ];

    public function setPublishedAtAttribute($published_at)
    {
        $this->attributes['published_at'] = trim($published_at) == '' ? null : trim($published_at);
    }

    public function setDateStartAttribute($date_start)
    {
        $this->attributes['date_start'] = $date_start ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $date_start) : null;
    }

    public function setDateEndAttribute($date_end)
    {
        $this->attributes['date_end'] = $date_end ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $date_end) : null;
    }

    public function setScheduledAtAttribute($scheduled_at)
    {
        $this->attributes['scheduled_at'] = $scheduled_at ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $scheduled_at) : null;
    }

    public function getTranslationsAttribute()
    {
        return Event::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function getTagsStringAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }

    public function admin() {
        return $this->belongsTo('App\Daran\Models\Admin');
    }

    public function category() {
        return $this->belongsTo('App\Daran\Models\EventCategory', 'event_category_id');
    }

    public function province() {
        return $this->belongsTo('App\Daran\Models\Province');
    }

    public function event_attachments() {
        return $this->hasMany('App\Daran\Models\EventAttachment');
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
     * Scope a query to only include event of the current lang.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }

    /**
     * Scope a query to only include currently published event.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('state','!=','draft')->where('published_at','<=',date('Y-m-d H:i'));
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
