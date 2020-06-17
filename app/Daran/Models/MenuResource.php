<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class MenuResource extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'params' => 'array'
    ];

}
