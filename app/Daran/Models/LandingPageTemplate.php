<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageTemplate extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function landing_pages() {
        return $this->hasMany('App\Daran\Models\LandingPage');
    }

}
