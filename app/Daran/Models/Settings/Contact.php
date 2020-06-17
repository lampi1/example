<?php

namespace App\Daran\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'settings_contacts';
    public $timestamps = false;

    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo('App\Models\Form');
    }
}
