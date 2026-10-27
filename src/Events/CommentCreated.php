<?php

namespace Pondol\Bbs\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Pondol\Bbs\Models\BbsArticles;
use Pondol\Bbs\Models\BbsComments;
use Pondol\Bbs\Models\BbsTables;

class CommentCreated
{
    use Dispatchable, SerializesModels;

    public $comment;

    public $article;

    public $cfg;

    public $user;

    public function __construct(BbsComments $comment, BbsArticles $article, BbsTables $cfg, $user = null)
    {
        $this->comment = $comment;
        $this->article = $article;
        $this->cfg = $cfg;
        $this->user = $user ?? auth()->user();
    }
}
