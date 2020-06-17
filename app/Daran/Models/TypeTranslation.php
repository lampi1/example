<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TypeTranslation extends Model
{
    use HasSlug;

    public $timestamps = false;

    protected $fillable = [
        'name', 'description', 'slug', 'meta_title', 'meta_description', 'og_title', 'og_description'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name','locale'])
            ->saveSlugsTo('slug');
            //->doNotGenerateSlugsOnUpdate();
    }

}
