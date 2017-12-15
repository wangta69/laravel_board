<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;



class AdminController extends \Pondol\Bbs\AdminController
{
    // 기본 라우트 이름
    protected $baseRouteName = '';
    
    // BBS Items per Page
    protected $itemsPerPage = 10;
    

    
}
