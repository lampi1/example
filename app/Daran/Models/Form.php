<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Cloner\Cloneable;

class Form extends Model
{
    use Cloneable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $clone_exempt_attributes = [];

    public function form_results()
    {
        return $this->hasMany('App\Daran\Models\FormResult');
    }

    public function getTranslationsAttribute()
    {
        return Form::where('locale_group','=',$this->locale_group)->where('locale','!=',$this->locale)->get();
    }

    public function scopeLocale($query)
    {
        return $query->where('locale', \App::getLocale());
    }
}
