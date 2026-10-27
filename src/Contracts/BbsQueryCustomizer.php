<?php

namespace Pondol\Bbs\Contracts;

interface BbsQueryCustomizer
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query  게시글 쿼리 빌더
     * @param  \Pondol\Bbs\Models\BbsTables  $cfg  게시판 설정 모델
     * @param  \Illuminate\Http\Request  $request  현재 HTTP 요청 객체
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indexQuery($query, $cfg, $request);
}
