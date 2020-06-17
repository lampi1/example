<?php

namespace App\Daran\Models;

use Illuminate\Database\Eloquent\Model;
use App\Daran\Events\ItemUpdated;

class ItemTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['description', 'meta_title', 'meta_description', 'og_title', 'og_description','material','color','sizes'];

    protected $dispatchesEvents = [
        'updated' => ItemUpdated::class,
    ];

}
