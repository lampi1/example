<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * Get the province of the model.
     */
    public function province()
    {
        return $this->belongsTo('App\Daran\Models\Province');
    }


}
