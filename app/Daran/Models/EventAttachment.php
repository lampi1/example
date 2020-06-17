<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttachment extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function event() {
        return $this->belongsTo('App\Daran\Models\Event');
    }
}
