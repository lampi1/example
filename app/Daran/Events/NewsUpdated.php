<?php

namespace App\Daran\Events;

use App\Daran\Models\News;
use Illuminate\Queue\SerializesModels;

class NewsUpdated
{
    use SerializesModels;

    public $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }
}
