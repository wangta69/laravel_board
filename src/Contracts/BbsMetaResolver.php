<?php

namespace Pondol\Bbs\Contracts;

interface BbsMetaResolver
{
    /**
     * @param  \Pondol\Meta\Meta  $meta  현재 메타 객체
     * @param  string  $type  'index' 또는 'show'
     * @param  object  $cfg  게시판 설정 (BbsTables 모델)
     * @param  object|null  $article  게시글 데이터 (BbsArticles 모델)
     */
    public function resolve($meta, $type, $cfg, $article = null);
}
