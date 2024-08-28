<?php
namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Response;
use Auth;

use App\Http\Controllers\Controller;
use Pondol\Bbs\Models\BbsItemComment;
use Pondol\Bbs\Traits\ItemCommentTrait;


// use Pondol\Bbs\BbsService;

class ItemCommentController extends Controller {
  use ItemCommentTrait;
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
    $result = $this->store($request, $item, $item_id, $parent_id);

    if($request->ajax()){
      return Response::json($result, 200);
    }
    //return view('welcome');
    return redirect()->back();
  }

  // update
  public function _update(Request $request, $tbl_name, $article, Comments $comment){

    $this->update($request, $tbl_name, $article, $comment);
    if($request->ajax()){
      return Response::json($result, 200);
    }
    //return view('welcome');
    return redirect()->back();

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
  public function _destroy(Request $request, $tbl_name, Articles $article, Comments $comment)
  {

    $this->destroy($article, $comment);
    if($request->ajax()){
      return Response::json($result, 200);
    }
    //return view('welcome');
    return redirect()->back();
  }
}
