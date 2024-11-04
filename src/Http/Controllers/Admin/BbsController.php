<?php

namespace Pondol\Bbs\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;

use App\Http\Controllers\Controller;

use Pondol\Bbs\BbsService;

use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsConfig;
use Pondol\Bbs\Traits\BbsTrait;

class BbsController extends Controller
{

  use BbsTrait;

  public function __construct(
    BbsService $bbsSvc 
  )
  {
    $this->bbsSvc = $bbsSvc;
    // $this->middleware('auth');

    // $this->middleware(function ($request, $next) {
    //   $value = config('pondol-bbs.admin_roles'); // administrator
    //   if (Auth::check()) {
    //     if(!$this->bbsSvc->hasRoles($value))
    //       return redirect('');
    //   } else {
    //     return redirect('');
    //   }
    //   return $next($request);
    // });
  }

  /**
   * 게시물 리스트
   */
  public function index(Request $request, $tbl_name) {
    // 사용자 정의 시작
    // 아래처럼 게시판별 필요한 추가 내용이 있을 경우 처리한다.
      // if ($cfg->table_name === 'qna') {
      //   $articles = $articles->leftjoin('users as u', function($join){
      //       $join->on('u.id', '=', 'bbs_articles.user_id');
      //   })->addSelect(
      //     'bbs_articles.id', 'bbs_articles.title',  'bbs_articles.writer', 'bbs_articles.created_at', 'bbs_articles.comment_cnt','u.email');
      // }
    // 사용자 정의 끝

    $result =  $this->_index($request, $tbl_name);

    if(isset($result['error'])) {
      if ($result['error'] == 'login') {
        return redirect()->route('login');
      }
    }
    // 
    // 레이아웃 정보 가져오기
    // print_r($result)
    // exit;
    $this->getLayout($result['cfg']);
    // echo 'bbs.templates.admin.'.$result['cfg']->skin_admin.'.index';
    // exit;
    // return view('bbs.templates.admin.basic-gallery.index', $result);
    return view('bbs.templates.admin.'.$result['cfg']->skin_admin.'.index', $result);
    
  }// bbs.admin.templates

  /**
   * 사용자 정의 (이곳에서 사용자 정의 처리) 기조 BbsController.php에 있는 내용을 가졍옮
   */

   

  /**
   * 게시물 보기
   */
  public function show(Request $request, $tbl_name, Articles $article) {

    $result =  $this->_show($request, $tbl_name, $article);

    if(isset($result['error'])) {
      if ($result['error'] == 'password') {
        return view('bbs.templates.admin.'.$result['cfg']->skin_admin.'.password-confirm', ['tbl_name'=>$tbl_name, 'article'=>$article->id]);
      }
    }
    // 레이아웃 정보 가져오기
    $this->getLayout($result['cfg']);
    return view('bbs.templates.admin.'.$result['cfg']->skin_admin.'.show', $result);
  }

  public function create(Request $request, $tbl_name) {
    $result =  $this->_create($request, $tbl_name);
    // 레이아웃 정보 가져오기
    $this->getLayout($result['cfg']);
    return view('bbs.templates.admin.'.$result['cfg']->skin_admin.'.create', $result);
  }

  public function store(Request $request, $tbl_name) {

    // if ($validator->fails()) return ['error'=>'validation', 'errors'=>$validator->errors()];
    $result =  $this->_store($request, $tbl_name);
    if(isset($result['error'])) {
      if ($result['error'] == 'validation') {
        return redirect()->back()->withInput()->withErrors($result['errors']);
      }
    }

    $enable_password = $result[2]->enable_password;
    if ($enable_password) {
      return redirect()->route('bbs.admin.tbl.index', [$result[0]]);
    } else {
      return redirect()->route('bbs.admin.tbl.show', [$result[0], $result[1]]);
    }
  }

  public function edit(Request $request, $tbl_name, Articles $article) {
    $result =  $this->_edit($request, $tbl_name, $article);
    $this->getLayout($result['cfg']);
    return view('bbs.templates.admin.'.$result['cfg']->skin_admin.'.create', $result);
  }

  public function update(Request $request, $tbl_name, Articles $article) {
    $result =  $this->_update($request, $tbl_name, $article);
    return redirect()->route('bbs.admin.tbl.show', $result);
  }

  public function destroy(Request $request, $tbl_name, Articles $article) {
    $result =  $this->_destroy($request, $tbl_name, $article);
    
    if($request->ajax()){
      return response()->json($result, 200);//500, 203
    } else {
      return redirect()->route('bbs.admin.tbl.index', [$tbl_name]);
    }
  }

  public function passwordConfirm(Request $request, $tbl_name, Articles $article) {
    $result =  $this->_passwordConfirm($request, $tbl_name, $article);

    if(isset($result['error'])) {
      if ($result['error'] == 'validation') {
        return redirect()->back()->withInput()->withErrors($result['errors']);
      }
    }

    return redirect()->route('bbs.admin.tbl.show', [$tbl_name, $article->id]);
  }

  private function getLayout(&$cfg) {
    $config = BbsConfig::get();
    foreach($config as $v) {
      switch($v->k) {
        case 'extends':
          $cfg->extends = $v->v; break;
        case 'section':
          $cfg->section = $v->v; break;
      }
    }
  }

  public function preIndex($tbl_name) {
    return $this->_preIndex($tbl_name);
  }
}
