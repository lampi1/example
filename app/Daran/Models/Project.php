<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Cloner\Cloneable;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\PageUpdated;

class Project extends Model implements HasMeta
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
        return Project::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function admin()
    {
        return $this->belongsTo('App\Daran\Models\Admin');
    }

    public function project_components()
    {
        return $this->hasMany('App\Daran\Models\ProjectComponent');
    }

    public function category() {
        return $this->belongsTo('App\Daran\Models\ProjectCategory','project_category_id');
    }

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
