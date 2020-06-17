<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Daran\Cloner\Cloneable;

class Member extends Model
{
    use HasSlug;
    use Cloneable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at'
    ];

    /*protected $cloneable_relations = [
        'category',
        'type',
        'author'
    ];*/

    protected $clone_exempt_attributes = [
        'locale_group', 'slug', 'state'
    ];

    public function setPublishedAtAttribute($published_at)
    {
        $this->attributes['published_at'] = trim($published_at) == '' ? null : trim($published_at);
    }

    public function getTranslationsAttribute()
    {
        return Member::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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
     * Sets the attributes that can not be cloned before saving to db
     *
     * @return array
     */
    public function onCloning($src) {
		$this->state = 'draft';
	}


    /**
     * Scope a query to only include posts of the current lang.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }

    /**
     * Scope a query to only include currently published posts.
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
        if ($this->search == 0 || $this->state != 'public') {
    	    $this->unsearchable();
            return [];
        }

        return array_only($this->toArray(), ['id', 'name', 'job', 'email', 'content']);
    }

}
