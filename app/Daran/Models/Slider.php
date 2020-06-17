<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Cloner\Cloneable;

class Slider extends Model
{
    use Cloneable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $cloneable_relations = [
        'slides',
    ];

    protected $clone_exempt_attributes = [
        'locale_group'
    ];

    public function getTranslationsAttribute()
    {
        return Slider::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function slides()
    {
        return $this->hasMany('App\Daran\Models\Slide');
    }

    public function user()
    {
        return $this->belongsTo('App\Daran\Models\Admin','user_id');
    }


    /**
     * Scope a query to only include sliders of the current lang.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }

}
