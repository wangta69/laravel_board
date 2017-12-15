<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;

class BoardController extends \Pondol\Bbs\BbsController
{
    // Article Items per Page
    protected $itemsPerPage = 10;

    // 파일 업로드 경로
    protected $uploadPath = '../storage/app/board/';
}
