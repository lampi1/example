<?php

namespace App\Daran\Events;

use App\Daran\Models\Gallery;
use Illuminate\Queue\SerializesModels;

class GalleryUpdated
{
    use SerializesModels;

    public $gallery;

    public function __construct(Gallery $gallery)
    {
        $this->gallery = $gallery;
    }
}
