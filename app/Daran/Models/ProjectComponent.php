<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Cloner\Cloneable;
use App\Daran\Priority\HasPriority;

class ProjectComponent extends Model
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

    public function project()
    {
        return $this->belongsTo('App\Daran\Models\Project');
    }
    public function images()
    {
        return $this->hasMany('App\Daran\Models\ProjectComponentImage')->orderBy('priority','asc');
    }
}
