<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUse extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [

    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon');
    }
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
