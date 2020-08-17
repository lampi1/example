<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Daran\Cart\Contracts\Buyable;
use Illuminate\Support\Facades\Lang;
use App\Daran\Priority\HasPriority;
use App\Daran\MetaTags\HasMeta;
use App\Daran\Cloner\Cloneable;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Item extends Model implements Buyable, HasMeta
{
    use Translatable, SoftDeletes, Cloneable, HasPriority, HasSlug;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $translatedAttributes = ['description', 'meta_title', 'meta_description', 'og_title', 'og_description','material','color','sizes'];

    protected $appends = ['current_price','title'];

    protected $with = ['translations'];

    protected $guarded = [];

    protected $cloneable_relations = [
        'translations'
    ];

    protected $clone_exempt_attributes = [];

    public function getCurrentPriceAttribute()
    {
        return $this->getBuyablePrice();
    }

    public function getReservedPriceAttribute()
    {
        if(Auth::check() && Auth::user()->pricelist_id){
            $plist = $this->pricelists->where('id',Auth::user()->pricelist_id)->first();
            if($plist && $plist->pivot->price > 0){
                return $plist->pivot->price;
            }
        }
        return $this->price;
    }

    public function getIsWishlistedAttribute()
    {
        return (Auth::check()) ? Wishlist::where('user_id',Auth::user()->id)->where('item_id',$this->id)->count() > 0 : false;
    }

    public function family()
    {
        return $this->belongsTo('App\Daran\Models\Family');
    }
    public function category()
    {
        return $this->belongsTo('App\Daran\Models\Category');
    }
    public function subcategory()
    {
        return $this->belongsTo('App\Daran\Models\Subcategory');
    }

    public function pricelists()
    {
        return $this->belongsToMany('App\Daran\Models\Pricelist')->withPivot('price');
    }

    public function images()
    {
        return $this->hasMany('App\Daran\Models\ItemImage')->orderBy('priority','asc');
    }

    public function related()
    {
        return $this->belongsToMany('App\Daran\Models\Item', 'related_items', 'item_id', 'related_id')->where('published',1);
    }

    public function packaging_types()
    {
        return $this->belongsToMany('App\Daran\Models\PackagingType');
    }

    // public function colors()
    // {
    //     return $this->hasMany('App\Daran\Models\ItemColor')->orderBy('priority','asc');
    // }

    public function getBuyableIdentifier($options = null): int
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null): string
    {
        return $this->name;
    }

    public function getBuyablePrice($options = null): float
    {
        return $this->price - ($this->price*$this->discount/100);
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
        return $this->meta_description ?? ($this->description ?? $this->name);
    }
    public function getOgDescription() : string
    {
        return $this->og_description ?? $this->getMetaDescription();
    }
    public function getOgImage() : string
    {
        if($this->images->count()>0){
            return config('app.url')."/".$this->images->first()->filename;
        }

        return '';
    }

    public function scopePublished($query)
    {
        return $query;
    }
    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function getTranslations() : ?array
    {
        return $this->translations ? $this->translations->pluck('locale')->all() : null;
    }

    public function scopeSearch($query, $term)
    {
        $searchableTerm = $this->fullTextWildcards($term);
        // return $query->whereHas('translations', function ($q) use($searchableTerm){
        //     $q->selectRaw("MATCH (description) AGAINST (? IN BOOLEAN MODE) AS relevance_score",[$searchableTerm])->whereRaw('MATCH (description) AGAINST  (? IN BOOLEAN MODE)', $searchableTerm)->where('locale',Lang::getLocale());
        // })->orderBy('relevance_score','desc');
        return $query
        ->join('item_translations as t', function ($join) {
            $join->on('items.id', '=', 't.item_id')->where('t.locale', '=', Lang::getLocale());
        })
        ->join('categories as categories', function ($join) {
            $join->on('items.category_id', '=', 'categories.id');
        })
        ->join('category_translations as ct', function ($join) {
            $join->on('ct.category_id', '=', 'categories.id')->where('ct.locale', '=', Lang::getLocale());
        })
        ->selectRaw("items.*,(MATCH (items.name) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE) OR MATCH(t.material) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE) ) OR MATCH(ct.name) AGAINST ('".$searchableTerm."' IN BOOLEAN MODE)) AS relevance_score")
        ->whereRaw('(MATCH (items.name) AGAINST  ("'.$searchableTerm.'" IN BOOLEAN MODE) OR MATCH (t.material) AGAINST  ("'.$searchableTerm.'" IN BOOLEAN MODE)  OR MATCH (ct.name) AGAINST  ("'.$searchableTerm.'" IN BOOLEAN MODE))')
        ->where('published','1')->groupBy('items.id')->orderBy('relevance_score','desc');
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

    public function isSeoIndexable() : bool
    {
        return true;
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('slug');
            //->doNotGenerateSlugsOnUpdate();
    }
}
