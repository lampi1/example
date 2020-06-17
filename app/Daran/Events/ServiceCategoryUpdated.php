<?php

namespace App\Daran\Events;

use App\Daran\Models\ServiceCategory;
use Illuminate\Queue\SerializesModels;

class ServiceCategoryUpdated
{
    use SerializesModels;

    public $category;

    public function __construct(ServiceCategory $category)
    {
        $this->category = $category;
    }
}
