<?php
namespace Wangta69\Bbs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Response;
use Auth;

use Wangta69\Bbs\Models\BbsArticles as Articles;
use Wangta69\Bbs\Models\BbsComments as Comments;

class BbsCommentController  extends BbsExtendsCommentController {

  protected $bbsSvc;
  protected $cfg;
  public function __construct(BbsService $bbsSvc) {
      $this->bbsSvc   = $bbsSvc;
  }


/*
    * Store Comment to BBS
    *
    * @param String $tbl_name
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function _store(Request $request, $tbl_name, Articles $article, $comment_id)
  {

    // $parent_id = 0;
    if(!$tbl_name || !$article->id)
      abort(404, "Exception Message");

    $validator = Validator::make($request->all(), [
      'content' => 'required|min:2',
    ]); // |max:255

    if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

    $result = $this->store($tbl_name, $article, $comment_id);

    if($request->ajax()){
      return Response::json($result, 200);
    }
    //return view('welcome');
    return redirect()->route('bbs.show', [$tbl_name, $article->id]);
  }

  // update
  public function _update(Request $request, $tbl_name, $article, Comments $comment){

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
    * Delete Article
    * Step1 : delete files
    * Step2 : files table
    * Step3 : delete article
    * @param String $tbl_name
    * @param  int  $id
    * @return \Illuminate\Http\Response
    * ajax로 처러되며 리턴도 json type으로 처리
    */
  public function _destroy(Request $request, Articles $article, Comments $comment)
  {

    if (!$comment->isOwner(Auth::user())) {
      return Response::json(['error'=>'본인이 작성한 글만 삭제가능합니다.', "code"=>"001"], 200);
    }

    $result = $this->destroy($article, $comment);

    return Response::json($result, 200);
  }
}
