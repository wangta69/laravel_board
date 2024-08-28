<?php
namespace Pondol\Bbs\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Response;
use Auth;

use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsComments as Comments;

trait CommentTrait {

  // protected $bbsSvc;
  protected $cfg;
  // public function __construct() {
  //   $this->bbsSvc = \App::make('Pondol\Bbs\BbsService');
  // }


/*
    * Store Comment to BBS
    *
    * @param String $tbl_name
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function store($request, $tbl_name, $article, $comment_id)
  {
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    //본글에 대한 답변인지 댓글의 댓글인지 구분


    $comment = new Comments;
    if($comment_id){
      $parent_comment = Comments::find($comment_id);
      $comment->reply_depth = $this->get_reply_depth($parent_comment);//리플 depth는 asc 방식으로 처리한다(먼저 쓴글이 최근위로)
      $comment->order_num = $parent_comment->order_num;
    }else{
      // $comment->reply_depth   = 'A';
      $comment->order_num = $this->get_order_num($article->id);
      $comment->reply_depth = 'A';
    }

    $comment->writer = $request->get('writer');
    if (Auth::check()) {
      $comment->user_id = Auth::user()->id;
      $comment->writer = $comment->writer ? $comment->writer : Auth::user()->name;
    } else {
      $comment->user_id = 0;
    }

    $comment->bbs_articles_id = $article->id;//firt fill then update

    $comment->content = $request->get('content');
    $comment->parent_id = 0;//firt fill then update
    $comment->save();

    $comment->parent_id = $comment_id  ? $comment_id : $comment->id;
    $comment->save();

    $article->comment_cnt++;
    $article->save();

    return ['error'=>false];
  }

  // update
  public function update($request, $comment){
    $comment->content = $request->get('content');
    $comment->save();
    return ['error'=>false];
  }

  /*
    *
    */
  private function get_order_num($article_id){
    $order_num = Comments::where('bbs_articles_id', $article_id)->min('order_num');
    return $order_num ? $order_num-1:-1;
  }

  /**
   * @param Comment $comment 현재 저장하려는 글의 부모글을 전달
   */
  private function get_reply_depth($comment){
    //1. 부모글의 reply_lengh를 구한 후
    //2. 보모글의 order_num, 부모글의 reply_depth 를 가진 글중 부모글의 reply_depth.lenth 보다 길이가 하나더 큰 글을 가져온다.
    //만약 없으면 부모글의 reply_depth 에 A를 더하고 하니면
    //reply_depth 마지막 글자에 .chr(ord($reply_depth)+1); 을하여 부모글 depth와 함께 리턴한다.
    //이부분이 기존 mysql 에서는 가능하지만 다른 query 와의 호환성을 위해 프로그램으로 처리하는 방식으로 변경한달.
    $depth_strlen = (strlen($comment->reply_depth) + 1);
    $depth = Comments::where('bbs_articles_id', $comment->bbs_articles_id)
      ->where('order_num', $comment->order_num)
      ->where('reply_depth', 'LIKE', $comment->reply_depth.'%')
      ->whereRaw('LENGTH(reply_depth) = '.$depth_strlen)
      ->max('reply_depth');
    if($depth){
      $rtn = $comment->reply_depth.chr(ord(substr($depth, -1))+1);
    }else{
      $rtn = $comment->reply_depth."A";
    }
    return $rtn;
  }
  /*
    * Delete Article
    * Step1 : delete files
    * Step2 : files table
    * Step3 : delete article
    * @param String $tbl_name
    * @param  int  $id
    * @return \Illuminate\Http\Response
    * ajax로 처러되며 리턴도 json type으로 처리
    */
  public function destroy($article, $comment)
  {
    // find child

    // if child is not exitst

    $isChild = Comments::where('order_num', $comment->order_num)
    ->where('parent_id', $comment->id)->first();
    //1. delete comment

    if($isChild) {
      $comment->content = null;
      $comment->save();
    } else {
      $comment->delete();
    }

    // else update to only contents null

    $article->comment_cnt--;
    $article->save();

    //return redirect()->route('bbs.index', [$tbl_name, 'urlParams='.$urlParams->enc]);
    return Response::json(['error'=>false, 'comment'=>$comment], 200);
  }
}
