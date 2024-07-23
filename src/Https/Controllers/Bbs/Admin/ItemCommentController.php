<?php

namespace App\Http\Controllers\Bbs\Admin;
use Illuminate\Http\Request;
use Auth;

class ItemCommentController extends \Pondol\Bbs\ItemCommentBaseController
{

  // protected $itemsPerPage = 10;

   /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->middleware('auth');
    // $this->itemsPerPage = 10; // change table list count;
    $this->middleware(function ($request, $next) {
      $value = config('bbs.admin_roles'); // administrator
      if (Auth::check()) {
        // if(!BbsService::hasRoles($value))
        if(!$this->bbsSvc->hasRoles($value))
          return redirect('');
      } else {
        return redirect('');
      }
      return $next($request);
    });
  }

  /**
   * 게시물 리스트
   */
  public function _index(Request $request, $item) {
    $comments =  $this->index($request, $item)->select(
      'bbs_item_comments.user_id', 'bbs_item_comments.user_name', 'bbs_item_comments.content', 'bbs_item_comments.created_at'
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
    return view('bbs.admin.comment.story', ['comments'=>$comments, 'cfg'=>$cfg]);
  }
}
