<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * Get all the provinces of the model.
     */
    public function provinces()
    {
        return $this->hasMany('App\Daran\Models\Province');
    }


}
