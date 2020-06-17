<?php

namespace App\Daran\Events;

use App\Daran\Models\CategoryTranslation;
use Illuminate\Queue\SerializesModels;

class CategoryUpdated
{
    use SerializesModels;

    public $category;

    public function __construct(CategoryTranslation $category)
    {
        $this->category = $category;
    }
}
