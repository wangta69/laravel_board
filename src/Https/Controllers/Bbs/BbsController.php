<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;

class BbsController extends \Pondol\Bbs\BbsController
{
    // Article Items per Page
    protected $itemsPerPage = 10;
    //protected $blade_extends = null;
    protected $blade_extends = 'vendor.layouts.vendor';
    // 파일 업로드 경로
    protected $uploadPath = '../storage/app/board/';
}
