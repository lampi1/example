<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;

class Redirection extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public static function findByOrigin($path): ?Redirection
    {
        return static::where('from_uri', $path === '/' ? $path : trim($path, '/'))->orderBy('id','desc')->first();
    }
}
