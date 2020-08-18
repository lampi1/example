<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Priority\HasPriority;

class PackagingType extends Model
{
    use HasPriority;

    public $timestamps = false;
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

         static::creating(function ($packagingType) {
             $priority = static::max('priority');
             $packagingType->priority = $priority ? ++$priority : 0;
        });
    }
}
