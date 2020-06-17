<?php

namespace App\Daran\Events;

use App\Daran\Models\Service;
use Illuminate\Queue\SerializesModels;

class ServiceUpdated
{
    use SerializesModels;

    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
