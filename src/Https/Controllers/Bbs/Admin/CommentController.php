<?php
namespace App\Http\Controllers\Bbs\Admin;

use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Traits\CommentTrait;
use Pondol\Bbs\BbsService;
class CommentController extends Controller
{

  use CommentTrait;
    /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(BbsService $bbsSvc )
  {
    $this->bbsSvc = $bbsSvc;
    $this->middleware('auth');

    $this->middleware(function ($request, $next) {
      $value = config('bbs.admin_roles'); // administrator
      if (Auth::check()) {
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
    if($request->ajax()){
      return response()->json($result, 200);//500, 203
    } else {
      return redirect()->route('bbs.admin.tbl.show', [$tbl_name, $article->id]);
    }

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
