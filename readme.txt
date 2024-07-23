새로운 스킨을 적용는 또다른 방식

<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Auth;
use Pondol\Bbs\BbsService;

class AnnouncementController extends \Pondol\Bbs\BbsController
{
    // Article Items per Page
    protected $itemsPerPage = 10;
    protected $deaultUrlParams = array('blade_extends' =>'pages/ko/Announcement/Announcement', 'class' =>'');
    // 파일 업로드 경로
    protected $uploadPath = '../storage/app/board/';


    public function bbsIndex(Request $request)
    {
        return $this->index($request, 'notice');
    }

}
