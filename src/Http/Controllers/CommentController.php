<?php
namespace Pondol\Bbs\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Response;
use Auth;
use App\Http\Controllers\Controller;

use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsComments as Comments;
use Pondol\Bbs\Traits\CommentTrait;
use Pondol\Bbs\BbsService;

class CommentController  extends Controller {

  use CommentTrait;

  protected $cfg;
  public function __construct(BbsService $bbsSvc) {
    $this->bbsSvc = $bbsSvc;
  }


/*
    * Store Comment to BBS
    *
    * @param String $tbl_name
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function store(Request $request, $tbl_name, Articles $article, $comment_id)
  {

    // $parent_id = 0;
    if(!$tbl_name || !$article->id)
      abort(404, "Exception Message");

    $validator = Validator::make($request->all(), [
      'content' => 'required|min:2',
    ]); // |max:255

    if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());


    $result = $this->_store($request, $tbl_name, $article, $comment_id);

    if($request->ajax()){
      return Response::json($result, 200);
    }
    return redirect()->route('bbs.show', [$tbl_name, $article->id]);
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

    $result = $this->_update($request, $comment);

    return Response::json($result, 200);

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
  public function destroy(Request $request, $tbl_name, Articles $article, Comments $comment)
  {

    if (!$comment->isOwner(Auth::user())) {
      return Response::json(['error'=>'본인이 작성한 글만 삭제가능합니다.', "code"=>"001"], 200);
    }

    $result = $this->_destroy($article, $comment);

    return Response::json($result, 200);
  }
}
