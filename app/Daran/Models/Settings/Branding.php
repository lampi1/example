<?php

namespace App\Daran\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Branding extends Model
{
    protected $table = 'settings_brandings';
    public $timestamps = false;

    protected $guarded = [];

    public static function createFromDefault($lang){
        $brand = new Branding();
        $brand->lang = $lang;
        $default = Branding::find(1);
        if ($default) {
            $brand->title = $default->title;
            $brand->favicon = $default->favicon;
            $brand->favicon_iphone = $default->favicon_iphone;
            $brand->favicon_ipad = $default->favicon_ipad;
            $brand->logo = $default->logo;
            $brand->logo_iphone = $default->logo_iphone;
            $brand->logo_ipad = $default->logo_ipad;
            $brand->site_title = $default->site_title;
            $brand->site_description = $default->site_description;
        }
        return $brand;
    }
}
