<?php

namespace Pondol\Bbs\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Pondol\Bbs\Models\BbsArticles;
use Pondol\Bbs\Models\BbsTables;

class ArticleCreated
{
    use Dispatchable, SerializesModels;

    public $article;

    public $cfg;

    public $user;

    public function __construct(BbsArticles $article, BbsTables $cfg, $user = null)
    {
        $this->article = $article;
        $this->cfg = $cfg;
        $this->user = $user ?? auth()->user();
    }
}
