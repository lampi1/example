<?php

namespace App\Daran\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'settings_seo';
    public $timestamps = false;

    protected $guarded = [];

    public static function createFromDefault($type){
        $seo = new Seo();
        $seo->type = $type;
        $default = Seo::where('type','=','default')->first();
        if ($default) {
            $seo->title = $default->title;
            $seo->description = $default->description;
            $seo->og_title = $default->og_title;
            $seo->og_description = $default->og_description;
            $seo->og_type = $default->og_type;
            $seo->og_image = $default->og_image;
            $seo->og_author = $default->og_author;
        }
        return $seo;
    }
}
