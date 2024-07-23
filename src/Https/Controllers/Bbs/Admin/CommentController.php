<?php
namespace App\Http\Controllers\Bbs\Admin;

use Illuminate\Http\Request;
use Auth;

use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsComments as Comments;

class CommentController extends \Pondol\Bbs\CommentBaseController
{

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

  public function _store(Request $request, $tbl_name, Articles $article, $comment_id) {
    $result =  $this->store($request, $tbl_name, $article, $comment_id);
    return redirect()->route('bbs.admin.tbl.show', [$tbl_name, $article->id]);
  }

  public function _update(Request $request, $tbl_name, Articles $article, Comments $comment) {
    $result =  $this->update($comment);
    return \Response::json($result, 200);
  }

  public function _destroy(Request $request, $tbl_name, Articles $article, Comments $comment) {
    $result =  $this->destroy($article, $comment);
    return \Response::json($result, 200);
  }
}
