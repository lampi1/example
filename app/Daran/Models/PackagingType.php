<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Priority\HasPriority;

class PackagingType extends Model
{
    use HasPriority;

    public $timestamps = false;
    protected $guarded = [];

    public function items()
    {
        return $this->belongsToMany('App\Daran\Models\Item');
    }
}
