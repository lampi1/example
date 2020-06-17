<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class ShippingMethod extends Model
{
    use Translatable;

    public $timestamps = false;
    public $translatedAttributes = ['name','description'];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [

    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    // (optionaly)
    protected $with = ['translations'];
}
