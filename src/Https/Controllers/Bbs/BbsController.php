<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Auth;
use Wangta69\Bbs\BbsService;

class BbsController extends \Wangta69\Bbs\BbsController
{
    // Article Items per Page
    protected $itemsPerPage = 10;
    protected $deaultUrlParams = array('blade_extends' =>'bbs::layouts.default', 'class' =>'');
    // 파일 업로드 경로
    protected $uploadPath = '../storage/app/board/';
}
