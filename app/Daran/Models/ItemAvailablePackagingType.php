<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class ItemAvailablePackagingType extends Model
{
    public $timestamps = false;

    protected $guarded = [

    ];

    public function item()
    {
        return $this->belongsTo('App\Daran\Models\Item');
    }

    public function packaging_type()
    {
        return $this->belongsTo('App\Daran\Models\PackagingType','packaging_type_id');
    }

    public function base_packaging_type()
    {
        return $this->belongsTo('App\Daran\Models\PackagingType','base_packaging_type_id');
    }
}
