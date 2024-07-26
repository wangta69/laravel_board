<?php
namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Response;
use Auth;

use App\Http\Controllers\Controller;
use Pondol\Bbs\Models\BbsItemComment;
use Pondol\Bbs\ItemCommentBase;


// use Pondol\Bbs\BbsService;

class ItemCommentController extends Controller {
  use ItemCommentBase;
  protected $cfg;
  public function __construct() {

  }


/*
    * Store Comment to BBS
    *
    * @param String $tbl_name
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function _store(Request $request, $item, $item_id, $parent_id=0)
  {

    $user = $request->user();
    if (!$user) {
      if($request->ajax()){
        return Response::json(['error'=>trans('bbs::messages.message.LOGIN')], 203);
      } else {
        
        return redirect()->back()->withErrors(['error'=>['message'=>trans('bbs::messages.message.LOGIN')]]);
      }
    }
    // $comment_id = $request->input('comment_id', 0);
    // $parent_id = 0;
    if(!$item || !$item_id)
      abort(404, "Exception Message");

    $validator = Validator::make($request->all(), [
      'content' => 'required|min:2',
    ]); // |max:255

    if ($validator->fails()) {
      if($request->ajax()){
        return Response::json(['error'=>'validationErrors', 'validation'=>$validator->errors()], 203);
      } else {
        return redirect()->back()->withErrors($validator->errors());
      }
      
    }
    $comment = new BbsItemComment;
    $comment->item = $item;
    $comment->item_id = $item_id;//firt fill then update
    $comment->writer = $request->get('writer');
    $comment->content = $request->get('content');

    $result = $this->store($comment, $item_id, $parent_id);

    if($request->ajax()){
      return Response::json($result, 200);
    }
    //return view('welcome');
    return redirect()->back();
  }

  // update
  public function update(Request $request, $tbl_name, $article, Comments $comment){

    if(!$tbl_name || !$comment->id)
      return Response::json(['result'=>false, "code"=>"001", 'message'=>'필요값이 충분하지 않습니다.'], 203);

    $validator = Validator::make($request->all(), [
      'content' => 'required|min:2',
    ]);
    // |max:255

    if ($validator->fails())
      return Response::json(['result'=>false, "code"=>"002", 'message'=>$validator->errors()], 203);

    if (!$comment->isOwner(Auth::user()))
      return Response::json(['result'=>false, "code"=>"003", 'message'=>'본인이 작성한 글만 수정가능합니다.'], 203);

    $comment->content = $request->get('content');
    $comment->save();

    return Response::json(['result'=>true, "code"=>"000", 'message'=>''], 200);

  }

  /*
    *
    */
  // private function get_order_num($item, $item_id){
  //   $order_num = BbsItemComment::where('item', $item)->where('item_id', $item_id)->min('order_num');
  //   return $order_num ? $order_num-1:-1;
  // }

  /**
   * @param Comment $comment 현재 저장하려는 글의 부모글을 전달
   */
  // private function get_reply_depth($comment){
  //   //1. 부모글의 reply_lengh를 구한 후
  //   //2. 보모글의 order_num, 부모글의 reply_depth 를 가진 글중 부모글의 reply_depth.lenth 보다 길이가 하나더 큰 글을 가져온다.
  //   //만약 없으면 부모글의 reply_depth 에 A를 더하고 하니면
  //   //reply_depth 마지막 글자에 .chr(ord($reply_depth)+1); 을하여 부모글 depth와 함께 리턴한다.
  //   //이부분이 기존 mysql 에서는 가능하지만 다른 query 와의 호환성을 위해 프로그램으로 처리하는 방식으로 변경한달.
  //   $depth_strlen = (strlen($comment->reply_depth) + 1);
  //   $depth = BbsItemComment::where('bbs_articles_id', $comment->bbs_articles_id)
  //             ->where('order_num', $comment->order_num)
  //             ->where('reply_depth', 'LIKE', $comment->reply_depth.'%')
  //             ->whereRaw('LENGTH(reply_depth) = '.$depth_strlen)
  //             ->max('reply_depth');
  //   if($depth){
  //     $rtn = $comment->reply_depth.chr(ord(substr($depth, -1))+1);
  //   }else{
  //     $rtn = $comment->reply_depth."A";
  //   }
  //   return $rtn;
  // }
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
  public function destroy(Request $request, $tbl_name, Articles $article, Comments $comment)
  {

    if (!$comment->isOwner(Auth::user())) {
      return Response::json(['error'=>'본인이 작성한 글만 삭제가능합니다.', "code"=>"001"], 200);
    }

    //1. delete comment
    $comment->delete();

    $article->comment_cnt--;
    $article->save();

    //return redirect()->route('bbs.index', [$tbl_name, 'urlParams='.$urlParams->enc]);
    return Response::json(['error'=>false, "code"=>"000", 'comment'=>$comment], 200);
  }
}
