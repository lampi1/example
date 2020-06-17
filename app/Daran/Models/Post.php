<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use App\Daran\Cloner\Cloneable;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Events\PostUpdated;

class Post extends Model implements HasMeta
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
        'updated' => PostUpdated::class,
    ];

    public function setPublishedAtAttribute($published_at)
    {
        $this->attributes['published_at'] = trim($published_at) == '' ? null : trim($published_at);
    }

    public function getTranslationsAttribute()
    {
        return Post::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function getTagsStringAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }

    public function user() {
        return $this->belongsTo('App\Daran\Models\Admin');
    }

    public function category() {
        return $this->belongsTo('App\Daran\Models\PostCategory','post_category_id');
    }

    public function post_attachments() {
        return $this->hasMany('App\Daran\Models\PostAttachment');
    }

    public function related()
    {
        return $this->belongsToMany('App\Daran\Models\Item', 'item_post', 'post_id', 'item_id')->where('stock','>',0)->where('published',1);
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name','locale'])
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
        if ($this->search == 0 || $this->state != 'published') {
    	    $this->unsearchable();
            return [];
        }
        return array_only($this->toArray(), ['id', 'title', 'abstract', 'content']);
    }

    public function scopeSearch($query, $term)
    {
        $searchableTerm = $this->fullTextWildcards($term);
        // return $query->whereHas('translations', function ($q) use($searchableTerm){
        //     $q->selectRaw("MATCH (description) AGAINST (? IN BOOLEAN MODE) AS relevance_score",[$searchableTerm])->whereRaw('MATCH (description) AGAINST  (? IN BOOLEAN MODE)', $searchableTerm)->where('locale',Lang::getLocale());
        // })->orderBy('relevance_score','desc');
        return $query
        ->join('post_categories as t', 'posts.post_category_id', '=', 't.id')->where('posts.locale',\App::getLocale())
        ->selectRaw("posts.*,(MATCH (t.name) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE) OR MATCH (posts.title) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE) OR MATCH(posts.abstract) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE)) AS relevance_score")
        ->whereRaw("(MATCH (t.name) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE) OR MATCH (posts.title) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE) OR MATCH (posts.abstract) AGAINST  ('".$searchableTerm."' IN BOOLEAN MODE))")
        ->orderBy('relevance_score','desc');
    }

    protected function fullTextWildcards($term)
    {
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
        $words = explode(' ', $term);

        foreach($words as $key => $word) {
            if(strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode( ' ', $words);

        return $searchTerm;
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
