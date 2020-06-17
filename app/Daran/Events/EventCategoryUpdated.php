<?php

namespace App\Daran\Events;

use App\Daran\Models\EventCategory;
use Illuminate\Queue\SerializesModels;

class EventCategoryUpdated
{
    use SerializesModels;

    public $category;

    public function __construct(EventCategory $category)
    {
        $this->category = $category;
    }
}
