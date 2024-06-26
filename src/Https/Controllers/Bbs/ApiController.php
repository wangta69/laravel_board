<?php

namespace App\Http\Controllers\Bbs\Admin;

use Illuminate\Http\Request;

use Wangta69\Bbs\Models\BbsArticles as Articles;
use Wangta69\Bbs\Models\BbsConfig;

class BbsController extends \Wangta69\Bbs\BbsExtendsController
{
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * 게시물 리스트
   */
  public function _index(Request $request, $tbl_name) {
    
    $preIndex = $this->preIndex($tbl_name);
    $articles = $preIndex->articles;
    $cfg = $preIndex->cfg;

    // 사용자 정의 시작
      // if ($cfg->table_name === 'qna') {
      //   $articles = $articles->leftjoin('users as u', function($join){
      //       $join->on('u.id', '=', 'bbs_articles.user_id');
      //   })->addSelect(
      //     'bbs_articles.id', 'bbs_articles.title',  'bbs_articles.user_name', 'bbs_articles.created_at', 'bbs_articles.comment_cnt', 'u.email');
      // }
    // 사용자 정의 끝

    $articles =  $this->index($request, $articles, $cfg);

    if(isset($result['error'])) {
      if ($result['error'] == 'login') {
        return redirect()->route('login');
      }
    }
    // 
    // 레이아웃 정보 가져오기
    $this->getLayout($cfg);
    return view('bbs.admin.templates.'.$cfg->skin_admin.'.index', ['articles'=>$articles, 'cfg'=>$cfg]);
  }

  /**
   * 사용자 정의 (이곳에서 사용자 정의 처리) 기조 BbsController.php에 있는 내용을 가졍옮
   */

   

  /**
   * 게시물 보기
   */
  public function _show(Request $request, $tbl_name, Articles $article) {

    $result =  $this->show($request, $tbl_name, $article);

   
    if(isset($result['error'])) {
      if ($result['error'] == 'password') {
        return view('bbs.admin.templates.'.$result['cfg']->skin_admin.'.password-confirm', ['tbl_name'=>$tbl_name, 'article'=>$article->id]);
      }
    }
    // 레이아웃 정보 가져오기
    $this->getLayout($result['cfg']);
    return view('bbs.admin.templates.'.$result['cfg']->skin_admin.'.show', $result);
  }

  public function _create(Request $request, $tbl_name) {
    $result =  $this->create($request, $tbl_name);
    // 레이아웃 정보 가져오기
    $this->getLayout($result['cfg']);
    return view('bbs.admin.templates.'.$result['cfg']->skin_admin.'.create', $result);
  }

  public function _store(Request $request, $tbl_name) {

    // if ($validator->fails()) return ['error'=>'validation', 'errors'=>$validator->errors()];
    $result =  $this->store($request, $tbl_name);
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

  public function _edit(Request $request, $tbl_name, Articles $article) {
    $result =  $this->edit($request, $tbl_name, $article);
    $this->getLayout($result['cfg']);
    return view('bbs.admin.templates.'.$result['cfg']->skin_admin.'.create', $result);
  }

  public function _update(Request $request, $tbl_name, Articles $article) {
    $result =  $this->update($request, $tbl_name, $article);
    return redirect()->route('bbs.admin.tbl.show', $result);
  }

  public function _destroy(Request $request, $tbl_name, Articles $article) {
    $result =  $this->destroy($request, $tbl_name, $article);
    return redirect()->route('bbs.admin.tbl.index', [$tbl_name]);
  }

  public function _passwordConfirm(Request $request, $tbl_name, Articles $article) {
    $result =  $this->passwordConfirm($request, $tbl_name, $article);

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
}
