<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class OrderDetail extends Model
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
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo('App\Daran\Models\Order');
    }

    /**
     * Get the item
     */
    public function item()
    {
        return $this->belongsTo('App\Daran\Models\Item')->withTrashed();
    }

    public function item_color()
    {
        return $this->belongsTo('App\Daran\Models\ItemColor')->withTrashed();
    }
}
