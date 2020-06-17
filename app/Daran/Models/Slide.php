<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Cloner\Cloneable;
use App\Daran\Priority\HasPriority;

class Slide extends Model
{
    use Cloneable, HasPriority;

    protected $guarded = [];
    public $timestamps = false;

    protected $clone_exempt_attributes = [
        'image',
        'image_xs',
        'image_sm',
        'image_mobile',
        'image_md',
        'image_lg',
    ];

    public function slider()
    {
        return $this->belongsTo('App\Daran\Models\Slider');
    }

}
