<?php

namespace App\Daran\Events;

use App\Daran\Models\ItemTranslation;
use Illuminate\Queue\SerializesModels;

class ItemUpdated
{
    use SerializesModels;

    public $item;

    public function __construct(ItemTranslation $item)
    {
        $this->item = $item;
    }
}
