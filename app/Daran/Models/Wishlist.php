<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [

    ];

    /**
     * Get the user that owns the wishlist.
     */
    public function user()
    {
        return $this->belongsTo('App\Daran\Models\User');
    }

    /**
     * Get the wished item.
     */
    public function item()
    {
        return $this->belongsTo('App\Daran\Models\Item')->withTrashed();
    }

}
