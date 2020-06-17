<?php

namespace App\Daran\Events;

use App\Daran\Models\NewsCategory;
use Illuminate\Queue\SerializesModels;

class NewsCategoryUpdated
{
    use SerializesModels;

    public $category;

    public function __construct(NewsCategory $category)
    {
        $this->category = $category;
    }
}
