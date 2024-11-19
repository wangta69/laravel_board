<?php

namespace Pondol\Bbs\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Auth;
use Pondol\Bbs\BbsService;
use App\Http\Controllers\Controller;
// use Pondol\Bbs\Models\BbsItemComment;
use Pondol\Bbs\Traits\ItemCommentTrait;

class ItemCommentController extends Controller
{

  use ItemCommentTrait;
  // protected $itemsPerPage = 10;

   /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(BbsService $bbsSvc )
  {
    $this->bbsSvc = $bbsSvc;
  }

  /**
   * 게시물 리스트
   */
  public function index(Request $request, $item) {
    $comments =  $this->_index($request, $item)->select(
      'bbs_item_comments.user_id', 'bbs_item_comments.writer', 'bbs_item_comments.content', 'bbs_item_comments.created_at'
    );
    // 아래와 같이 사용자정의를 하여 각각의 데이타를 가져와야 한다.
    switch($item) {
      case 'story':
        $comments->join('keywords', function($join){
          $join->on('bbs_item_comments.item_id', '=', 'keywords.id');
        })
        ->addSelect('keywords.title', 'keywords.path');
    }

    $comments =  $comments->orderBy('bbs_item_comments.id', 'desc')->paginate(20)->appends(request()->query());
    
    // print_r($comments);
    // if(isset($result['error'])) {
    //   if ($result['error'] == 'login') {
    //     return redirect()->route('login');
    //   }
    // }
    // exit;
    $cfg = $this->admin_extends();
    return view('bbs.components.comments.admin.story.comments', ['comments'=>$comments, 'cfg'=>$cfg]);
  }
}
