<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'parameters' => 'array'
    ];

    public function admin()
    {
        return $this->belongsTo('App\Daran\Models\Admin');
    }

    public function menu()
    {
        return $this->belongsTo('App\Daran\Models\Menu');
    }

    public function parent()
    {
        return $this->belongsTo('App\Daran\Models\MenuItem','parent_id','id');
    }

    public function children()
    {
        return $this->hasMany('App\Daran\Models\MenuItem','parent_id','id');
    }

    public function menu_resource()
    {
        return $this->hasMany('App\Daran\Models\MenuResource');
    }

}
