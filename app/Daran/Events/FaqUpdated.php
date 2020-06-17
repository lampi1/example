<?php

namespace App\Daran\Events;

use App\Daran\Models\Faq;
use Illuminate\Queue\SerializesModels;

class FaqUpdated
{
    use SerializesModels;

    public $faq;

    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
    }
}
