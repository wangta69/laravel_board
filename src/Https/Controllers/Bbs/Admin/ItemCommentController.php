<?php

namespace App\Http\Controllers\Bbs\Admin;

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
  public function _index(Request $request, $type) {
    $result =  $this->index($request, $type);

    // if(isset($result['error'])) {
    //   if ($result['error'] == 'login') {
    //     return redirect()->route('login');
    //   }
    // }
    return view('bbs.admin.comment.index');
  }
}
