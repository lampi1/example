<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class NewsAttachment extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function news() {
        return $this->belongsTo('App\Daran\Models\News');
    }
}
