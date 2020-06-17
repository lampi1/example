<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Cloner\Cloneable;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\PageUpdated;

class Page extends Model implements HasMeta
{
    use HasTags, HasSlug, Cloneable;

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
        'updated' => PageUpdated::class,
    ];

    public function setPublishedAtAttribute($published_at)
    {
        $this->attributes['published_at'] = trim($published_at) == '' ? null : trim($published_at);
    }

    public function getTagsStringAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }

    public function getTranslationsAttribute()
    {
        return Page::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function admin()
    {
        return $this->belongsTo('App\Daran\Models\Admin');
    }

    public function template()
    {
        return $this->belongsTo('App\Daran\Models\PageTemplate','page_template_id');
    }

    public function father()
    {
        return $this->belongsTo('App\Daran\Models\Page','father_page_id','id');
    }

    public function children()
    {
        return $this->hasMany('App\Daran\Models\Page','father_page_id','id');
    }

    public function slider() {
        return $this->belongsTo('App\Daran\Models\Slider');
    }

    public function page_attachments() {
        return $this->hasMany('App\Daran\Models\PageAttachment');
    }

    public function category() {
        return $this->belongsTo('App\Daran\Models\PageCategory','page_category_id');
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
     * Sets the attributes that can not be cloned after saving to db
     *
     * @return array
     */
    public function onCloned($src) {
        if ($src->tags) {
            $this->attachTags($src->tags);
            $this->save();
        }
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        if ($this->search == 0 || $this->state != 'published') {
    	    $this->unsearchable();
            return [];
        }

        return array_only($this->toArray(), ['id', 'title', 'abstract', 'content', 'visual_content_html']);
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
