<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Daran\Priority\HasPriority;

class ProjectComponentImage extends Model
{
    //use HasPriority;

    public $timestamps = false;

    protected $guarded = [

    ];

    public static function boot()
    {
        parent::boot();

         static::creating(function ($item) {
             $priority = static::where('project_component_id',$item->item_id)->max('priority');
             $item->priority = $priority ? ++$priority : 0;
        });
    }

    public function project_component()
    {
        return $this->belongsTo('App\Daran\Models\ProjectComponent');
    }
}
