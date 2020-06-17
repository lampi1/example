<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethodTranslation extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

}
