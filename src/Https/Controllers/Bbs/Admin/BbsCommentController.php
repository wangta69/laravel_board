<?php

namespace App\Http\Controllers\Bbs\Admin;

use Illuminate\Http\Request;

use Wangta69\Bbs\Models\BbsArticles as Articles;
use Wangta69\Bbs\Models\BbsComments as Comments;

class BbsCommentController extends \Wangta69\Bbs\BbsExtendsCommentController
{

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    public function _store(Request $request, $tbl_name, Articles $article, $comment_id) {
      $result =  $this->store($request, $tbl_name, $article, $comment_id);
      return redirect()->route('bbs.admin.tbl.show', [$tbl_name, $article->id]);
    }

    public function _update(Request $request, $tbl_name, Articles $article, Comments $comment) {
      $result =  $this->update($request, $tbl_name, $article, $comment);
      return \Response::json($result, 200);
    }

    public function _destroy(Request $request, $tbl_name, Articles $article, Comments $comment) {
      $result =  $this->destroy($request, $tbl_name, $article, $comment);
      return \Response::json($result, 200);
    }
}
