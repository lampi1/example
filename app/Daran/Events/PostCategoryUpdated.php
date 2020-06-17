<?php

namespace App\Daran\Events;

use App\Daran\Models\PostCategory;
use Illuminate\Queue\SerializesModels;

class PostCategoryUpdated
{
    use SerializesModels;

    public $category;

    public function __construct(PostCategory $category)
    {
        $this->category = $category;
    }
}
