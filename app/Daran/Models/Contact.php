<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $timestamps = false;

    protected $dates = ['privacy_at'];

    protected $guarded = [];

    // public function region()
    // {
    //     return $this->belongsTo('App\Models\Region');
    // }
    // public function province()
    // {
    //     return $this->belongsTo('App\Models\Province');
    // }
    // public function city()
    // {
    //     return $this->belongsTo('App\Models\City');
    // }
    // public function contract_type()
    // {
    //     return $this->belongsTo('App\Models\ContractType');
    // }
    // public function house_type()
    // {
    //     return $this->belongsTo('App\Models\HouseType');
    // }
}
