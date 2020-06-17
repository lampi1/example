<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function pages() {
        return $this->hasMany('App\Models\Page');
    }

}
