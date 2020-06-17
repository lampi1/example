<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSize extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [

    ];

    /**
     * The item of the size
     */
    public function item()
    {
        return $this->belongsTo('App\Daran\Models\Item');
    }
    public function size()
    {
        return $this->belongsTo('App\Daran\Models\Size');
    }
}
