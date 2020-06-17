<?php

namespace App\Daran\Events;

use App\Daran\Models\GalleryCategory;
use Illuminate\Queue\SerializesModels;

class GalleryCategoryUpdated
{
    use SerializesModels;

    public $category;

    public function __construct(GalleryCategory $category)
    {
        $this->category = $category;
    }
}
