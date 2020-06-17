<?php

namespace App\Daran\Events;

use App\Daran\Models\Event;
use Illuminate\Queue\SerializesModels;

class EventUpdated
{
    use SerializesModels;

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }
}
