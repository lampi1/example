<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class PageAttachment extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function page() {
        return $this->belongsTo('App\Models\Page');
    }
}
