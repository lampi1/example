<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * Get all the cities of the model.
     */
    public function cities()
    {
        return $this->hasMany('App\Daran\Models\Cities');
    }

    /**
     * Get the region of the model.
     */
    public function region()
    {
        return $this->belongsTo('App\Daran\Models\Region');
    }



}
