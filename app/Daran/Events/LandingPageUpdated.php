<?php

namespace App\Daran\Events;

use App\Daran\Models\LandingPage;
use Illuminate\Queue\SerializesModels;

class LandingPageUpdated
{
    use SerializesModels;

    public $page;

    public function __construct(LandingPage $page)
    {
        $this->page = $page;
    }
}
