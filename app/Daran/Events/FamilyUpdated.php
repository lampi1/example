<?php

namespace App\Daran\Events;

use App\Daran\Models\FamilyTranslation;
use Illuminate\Queue\SerializesModels;

class FamilyUpdated
{
    use SerializesModels;

    public $family;

    public function __construct(FamilyTranslation $family)
    {
        $this->family = $family;
    }
}
