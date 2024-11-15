<?php
namespace Pondol\Bbs\Http\Controllers\Admin;

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
  }

  public function store(Request $request, $tbl_name, Articles $article, $comment_id) {
    $result =  $this->_store($request, $tbl_name, $article, $comment_id);
    if($request->ajax()){
      return response()->json($result, 200);//500, 203
    } else {
      return redirect()->route('bbs.admin.tbl.show', [$tbl_name, $article->id]);
    }

  }

  public function update(Request $request, $tbl_name, Articles $article, Comments $comment) {
    $result =  $this->_update($comment);
    return \Response::json($result, 200);
  }

  public function destroy(Request $request, $tbl_name, Articles $article, Comments $comment) {
    $result =  $this->_destroy($article, $comment);
    return \Response::json($result, 200);
  }
}
