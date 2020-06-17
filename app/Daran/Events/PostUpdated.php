<?php

namespace App\Daran\Events;

use App\Daran\Models\Post;
use Illuminate\Queue\SerializesModels;

class PostUpdated
{
    use SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
