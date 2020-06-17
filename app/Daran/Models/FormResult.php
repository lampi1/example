<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class FormResult extends Model
{

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function form()
    {
        return $this->belongsTo('App\Daran\Models\Form');
    }
}
