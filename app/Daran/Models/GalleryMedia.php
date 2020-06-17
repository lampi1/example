<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Cloner\Cloneable;
use App\Daran\Priority\HasPriority;

class GalleryMedia extends Model
{
    use HasPriority;

    protected $guarded = [];
    public $timestamps = false;

    public function gallery()
    {
        return $this->belongsTo('App\Daran\Models\Gallery');
    }

}
