<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class OrderReturnDetail extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the order
     */
    public function order_return()
    {
        return $this->belongsTo('App\Daran\Models\OrderReturn');
    }

    /**
     * Get the order
     */
    public function order_detail()
    {
        return $this->belongsTo('App\Daran\Models\OrderDetail');
    }

    /**
     * Get the item
     */
    public function item()
    {
        return $this->belongsTo('App\Daran\Models\Item')->withTrashed();
    }


}
