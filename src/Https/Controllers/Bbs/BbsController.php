<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Auth;
use Pondol\Bbs\BbsService;

class BbsController extends \Pondol\Bbs\BbsController
{
    // Article Items per Page
    protected $itemsPerPage = 10;
    protected $deaultUrlParams = array('blade_extends' =>'bbs::layouts.default');
    // 파일 업로드 경로
    protected $uploadPath = '../storage/app/board/';
}
