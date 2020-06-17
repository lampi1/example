<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Daran\Priority\HasPriority;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Cloner\Cloneable;

class Category extends Model implements HasMeta
{
    use Translatable, Cloneable, HasPriority;

    public $timestamps = false;
    public $translatedAttributes = ['name','description','slug', 'meta_title', 'meta_description', 'og_title', 'og_description'];
    protected $appends = ['title'];
    protected $guarded = [];

    protected $with = ['translations'];

    protected $cloneable_relations = [
        'translations'
    ];

    protected $clone_exempt_attributes = [];



    public function family()
    {
        return $this->belongsTo('App\Daran\Models\Family');
    }

    public function subcategories()
    {
        return $this->hasMany('App\Daran\Models\Subcategory');
    }

    public function items()
    {
        return $this->hasMany('App\Daran\Models\Item');
    }

    public function scopePublished($query)
    {
        return $query;
    }
    public function getTitleAttribute()
    {
        return $this->family->name;
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
        return $this->image ? config('app.url')."/".$this->image : '';
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
