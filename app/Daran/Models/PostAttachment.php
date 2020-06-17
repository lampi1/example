<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function post() {
        return $this->belongsTo('App\Daran\Models\Post');
    }
}
