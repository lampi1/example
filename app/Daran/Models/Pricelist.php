<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class Pricelist extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany('App\Daran\Models\Category');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function items()
    {
        return $this->belongsToMany('App\Daran\Models\Item')->withPivot('price')->as('details');
    }

}
