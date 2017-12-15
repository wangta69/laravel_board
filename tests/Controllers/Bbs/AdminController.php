<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;

class AdminController extends \Pondol\Board\AdminController
{
    // 기본 라우트 이름
    protected $baseRouteName = '';
    
    // 한 화면에 표시할 리스트 개수
    protected $itemsPerPage = 10;
    

    
}
