<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Daran\Priority\HasPriority;

class ItemColor extends Model
{
    //use HasPriority;

    public $timestamps = false;

    protected $guarded = [

    ];

    public static function boot()
    {
        parent::boot();

         static::creating(function ($item) {
             $priority = static::where('item_id',$item->item_id)->max('priority');
             $item->priority = $priority ? ++$priority : 0;
        });
    }

    public function item()
    {
        return $this->belongsTo('App\Daran\Models\Item');
    }
}
