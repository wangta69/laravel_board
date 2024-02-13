<?php

namespace App\Http\Controllers\Bbs;

use Wangta69\Bbs\BbsController;
use Illuminate\Http\Request;
use Response;
use Validator;
use Auth;
use Illuminate\Support\Facades\Redis;
use Wangta69\Bbs\Models\BbsTables as Tables;
use Wangta69\Bbs\Models\BbsArticles as Articles;
use Wangta69\Bbs\Models\BbsComments as Comments;
use Wangta69\Bbs\Models\BbsFiles as Files;
// use App\Notifications\CountChanged;
use Wangta69\Bbs\BbsService;

// class ApiController extends BbsController
class ApiController extends \Wangta69\Bbs\BbsExtendsController
{

  protected $bbsSvc;
  protected $deaultUrlParams;

  public function __construct(BbsService $bbsSvc)
  {
    //$this->middleware('guest', ['except' => 'logout']);
    $this->bbsSvc = $bbsSvc;
  }

  /**
   * 리스트 가져오기
   */
  public function lists($tbl_name, Request $request)
  {
    $offset = $request->offset;
    $take = $request->input('take', 10);


    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
//    $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

    $articles = Articles::
      select('id', 'user_id', 'user_name', 'content', 'title', 'image', 'hit', 'comment_cnt', 'created_at')
      ->where('bbs_table_id', $cfg->id)
      ->orderBy('order_num');

    if ($tbl_name === 'qna') {
      $token = \JWTAuth::getToken();
      $user =  \JWTAuth::toUser($token);
      $articles = $articles->where('user_id', $user->id);
    }

    if (isset($offset)) {
      $articles = $articles
      ->skip($offset)
      ->take($take)
      ->get();
    } else {
      $articles = $articles
      ->paginate($take);
    }

    return response()->json([
      'error'=>false,
      'articles' => $articles
    ], 200);//500, 203
  }

}
