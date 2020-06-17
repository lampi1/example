<?php

namespace App\Daran\Events;

use App\Daran\Models\Page;
use Illuminate\Queue\SerializesModels;

class PageUpdated
{
    use SerializesModels;

    public $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }
}
