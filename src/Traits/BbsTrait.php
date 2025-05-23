<?php
namespace Pondol\Bbs\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use View;
use Cookie;
use File;
use Storage;
use Response;
use Auth;

use Pondol\Bbs\Models\BbsTables as Tables;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsComments as Comments;
use Pondol\Bbs\Models\BbsFiles as Files;

// use Pondol\Meta\Facades\Meta;
use Pondol\Bbs\BbsService;


trait BbsTrait  {


  // private $isAdmin;
  // private $cfg;
  /*
   * List Page
   *
   * @param String $tbl_name
   * @return \Illuminate\Http\Response
   */
  public function _index($request, $tbl_name)
  {

    // \DB::enableQueryLog();
    $preIndex = $this->preIndex($tbl_name);
    $articles = $preIndex->articles;
    $top_articles = Articles::where('bbs_table_id', $preIndex->cfg->id);
    $cfg = $preIndex->cfg;

    $f = $request->input('f', null); // Searching Field ex) title, content
    $s = $request->input('s', null); // Searching text
    

    $user = $request->user();
    if ($cfg->auth_list === 'login' &&  !$user) {
      return ['error'=>'login'];
    }
    
    // $top_articles = $all_articles->orderBy('order_num')->where('top', 1);
    $articles->orderBy('order_num')->where('top', 0);
    $top_articles = $top_articles->orderBy('order_num')->where('top', 1)->get();
    
    if ($s) {
      if(!$f) {
        $articles = $articles->when($s, function ($query, $s) {
          $query->whereFullText(['title', 'content'], $s, ['mode'=>'boolean']);
        }, function ($query) {
            $query->latest();
        });
      } else {
        $articles = $articles->where($f, 'like', '%'.$s.'%');
      }
    }

    $adminrole = config('pondol-bbs.admin_roles'); // administrator

    // 관리자 권한 및 본인에게만 데이타를 보여 준다.
    if ($cfg->enable_qna == '1') {
      $adminrole = config('pondol-bbs.admin_roles'); // administrator
      $hasrole = BbsService::hasRoles($adminrole);
      if (!$hasrole) { // admin 권한을 가지고 있지 않은 경우 본인 글만 디스플레이 한다.
        if (!$user) { // 로그인 페이지로 이동
          return ['error'=>'login'];
        } else {
          $articles = $articles->where('user_id', $user->id);
        }
      }
    }

    $articles = $articles->paginate($cfg->lists)
      ->appends(request()->query());
    //  print_r(\DB::getQueryLog()); // 'top_articles'=>$top_articles, 
    return ['error'=> false, 'articles'=>$articles, 'top_articles'=>$top_articles, 'cfg'=>$cfg];
  }

  /**
   * index를 가져올 전처리 작업 (select 등 다양한 경우에 대비하기위해 index를 가져오기 전에 먼저 선 작업을 한다.)
   */
  private function preIndex($tbl_name) {
    $obj = new \stdClass();
    $obj->cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
    $obj->articles = Articles::where('bbs_table_id', $obj->cfg->id);
    return $obj;
  }

  /**
  * API 호출시 직접 데이타 처리
  */
  public function _indexApi($request, $tbl_name)
  {
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    $articles = Articles::where('bbs_table_id', $cfg->id)
      ->orderBy('order_num')
      ->paginate($cfg->lists)
      ->appends(request()->query());
    return response()->json(['error'=>false, 'articles' => $articles, 'cfg'=>$cfg], 200);//500, 203

  }

  /*
   * Write Form Page
   *
   * @param String $tbl_name
   * @return \Illuminate\Http\Response
   */
  public function _create($request, $tbl_name)
  {

    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    //check permission
    $permission_result = $cfg->hasPermission('write');
    if(!$permission_result)
      abort(403, 'Unauthorized action.');

    return ['error'=>false, 'cfg'=>$cfg, 'article' => new Articles];
  }

  private function storeOrUpdateValidation($request, $tbl_name) {
    $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    //check permission
    $permission_result = $cfg->hasPermission('write');
    if(!$permission_result) {
      return ['error'=>'NotAuthenticated'];
    }

    
    $validator = Validator::make($request->all(), [
      'title' => 'required|min:2|max:100',
      // 'content' => 'required',
    ], [
      'title.*' => '2글자 이상의 제목을 입력해 주세요',
      'content.required' => '내용을 입력해 주세요',
      'password.required' => '패스워드를 입력해 주세요'
    ]);

    
    $validator->sometimes('content', 'required', function ($input) use ($request) {
      return !$request->no_content;
    });

    if(!$isAdmin) {
      $validator->sometimes('password', 'required', function ($input) use ($cfg) {
        return $cfg->enable_password == 1;
      });
    }

    if ($validator->fails()) {
      return ['error'=>'validation', 'errors'=>$validator->errors()];
    }

    return ['error'=>false, 'cfg'=>$cfg];
  }

  private function storeOrUpdate($request, $article) {

    $article->title = $request->get('title');
    $article->writer = $request->get('writer') ?? $article->writer;
    $article->password = $request->get('password');
    $article->content = $request->get('content');
    $article->text_type = $request->input('text_type', 'br');
    $article->keywords = $request->input('keywords');
    $article->bbs_category_id = $request->get('category');
    $article->top = $request->input('top', 0);



    return $article;
  }

  private function storeOrUpdateTrimContents($request, $article, $cfg) {
    $date_Ym = date("Ym", strtotime($article->created_at));//수정일경우 기존 데이타의 생성일을 기준으로 가져온다.

    //$filepath = 'bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;////5.6부터는 이렇게 처리하면 storage/app/public 이하로 들어간다.
    $filepath = 'public/bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;//5.5에서는 5.6버젼을 고려하여 public 을 상단에 더 붙혀 준다.
    if(is_array($request->file('uploads'))) {
      foreach ($request->file('uploads') as $index => $upload) {

        if ($upload == null) {
          continue;
        }

        // Delete exist files
        if (($file = $article->files->where('rank', $index)->first())) {
          Storage::delete($file->path_to_file);
          $file->delete();
        }

        $filename = $upload->getClientOriginalName();
        $fileextension = $upload->getClientOriginalExtension();
        $path=Storage::put($filepath, $upload); // //Storage::disk('local')->put($name,$file,'public');

        //save to database
        $file = new Files();
        $file->rank = $index;
        $file->bbs_articles_id = $article->id;
        $file->file_name = $filename;
        $file->path_to_file = $path;
        $file->name_on_disk = basename($path);
        $file->save();
      }
    }

    $this->contents_update($article, $cfg->id, $date_Ym);
    $this->set_representaion($article);
  }
  /*
    * Store to BBS
    *
    * @param String $tbl_name
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function _store($request, $tbl_name)
  {

    $validation = $this->storeOrUpdateValidation($request, $tbl_name);
    if($validation['error']) {
      return $validation;
    }

    $article = new Articles;
    $article = $this->storeOrUpdate($request, $article);

    $article->bbs_table_id = $validation['cfg']->id;
    // $article->writer = $request->get('writer');

    if (Auth::check()) {
      $article->user_id = Auth::user()->id;
      $article->writer = $article->writer ?? Auth::user()->name;
    } else {
      $article->user_id = 0;
    }

    $article->order_num = $this->get_order_num();
    $article->parent_id = 0;//firt fill then update
    $article->comment_cnt = 0;
    $article->save();
    $article->parent_id = $request->get('parent_id') ?? $article->id;
    $article->save();

    if($article->password) {
      Cookie::queue(Cookie::make('pass-'.$tbl_name.$article->id, '1'));
    }

    $this->storeOrUpdateTrimContents($request, $article, $validation['cfg']);

    return ['error'=>false, 'tbl_name'=>$tbl_name, 'article'=>$article];
  }

    /*
     * Modify Article
     *
     * @param  \Illuminate\Http\Request  $request
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function _update($request, $tbl_name, $article)
  {

    $validation = $this->storeOrUpdateValidation($request, $tbl_name);
    if($validation['error']) {
      return $validation;
    }

    $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
    if (!$article->isOwner(Auth::user()) && !$isAdmin) {
      return ['error'=>'NotAuthenticated'];
    }
         
    $article = $this->storeOrUpdate($request, $article);
    $article->save();
    $this->storeOrUpdateTrimContents($request, $article, $validation['cfg']);

    return ['error'=>false, 'tbl_name'=>$tbl_name, 'article'=>$article];
  }

    /*
     *
     */
  protected function get_order_num($params=null){
    $order_num = Articles::min('order_num');
    return $order_num ? $order_num-1:-1;
  }

  /**
   * 에디터에 이미지가 포함된 경우 이미지를 현재 아이템에 editor라는 폴더를 만들고 그곳에 모두 복사한다.
   * 그리고 contents에 포함된 링크 주소고 변경하여 데이타를 업데이트 한다.
     */
  protected function contents_update($article, $table_id, $date_Ym){

    $sourceDir = storage_path() .'/app/public/tmp/editor/'. session()->getId();
    $destinationDir = storage_path() .'/app/public/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor';

    $article->content = str_replace('/storage/tmp/editor/'.session()->getId(), '/storage/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor', $article->content);

    $article->save();

    $success = File::copyDirectory($sourceDir, $destinationDir);
    Storage::deleteDirectory('public/tmp/editor/'. session()->getId());
  }

  /**
   * 대표 이미지 설정
   */
  protected function set_representaion($article){
    $article->image = null;
    //$representaion_image = null;//1순위: 첨부화일에 이미지가 있을 경우, 2순위 : editor에 이미지가 있을 경우
    $representaion_image_array = ['jpeg', 'jpg', 'png', 'gif'];
    // foreach($article->files as $file) { // 이럴 경우 이전의 정보를 가지고 옮
    $files = Files::where('bbs_articles_id', $article->id)->get();
    foreach($files as $file) { 
      if($file->path_to_file){
        $tmp = explode('.', $file->path_to_file);
        $extension = end($tmp);
        if(in_array($extension, $representaion_image_array)){
          $article->image = $file->path_to_file;
          break;
        }
      }
    }

    if(!$article->image && $article->content){
      //2순위 : editor에 이미지가 있을 경우
      preg_match_all('/<img[^>]+>/i',$article->content, $result);
      // [0] => Array(
      // [0] => <img src="/Content/Img/stackoverflow-logo-250.png" width="250" height="70" alt="logo link to homepage" />
      // [1] => <img class="vote-up" src="/content/img/vote-arrow-up.png" alt="vote up" title="This was helpful (click again to undo)" />

      if($result && count($result) > 1){
        preg_match_all('/(src)=("[^"]*")/i',$result[0][0], $i_result);
        $src = str_replace(["\"", "/storage"], ["", "public"], $i_result[2][0]);

        $date_Ym = date("Ym", strtotime($article->created_at));//수정일경우 기존 데이타의 생성일을 기준으로 가져온다.
        $filepath = 'public/bbs/'.$article->bbs_table_id.'/'.$date_Ym.'/'.$article->id;//5.5에서는 5.6버젼을 고려하여 public 을 상단에 더 붙혀 준다.

        $contents = Storage::get($src);
        $name = substr($src, strrpos($src, '/') + 1);
        Storage::put($filepath."/".$name, $contents);
        $article->image = $filepath."/".$name;
        //로컬 경로로 파일 copy
      }
    }

    $article->save();
  }
  /*
    * Show Article
    *
    * @param String $tbl_name
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function _show($request, $tbl_name, $article)
  {
    $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    // 시간되면 이 부분은 좀더 고도화 필요
    $user = $request->user();
    if ($cfg->auth_read === 'login' &&  !$user) {
      return ['error'=>'login'];
    }

    if (!$isAdmin && $article->password && $request->cookie('pass-'.$tbl_name.$article->id) != '1') {
      return ['error'=>'password', 'cfg'=>$cfg, 'tbl_name'=>$tbl_name, 'article'=>$article];
    }

    if ($request->cookie($tbl_name.$article->id) != '1') {
      $article->hit ++;
      $article->save();
    }

    if($article->text_type == 'br') {
      $article->content = nl2br($article->content);
    }

    Cookie::queue(Cookie::make($tbl_name.$article->id, '1'));

    return ['error'=>false, 'article' => $article, 'cfg'=>$cfg, 'isAdmin'=>$isAdmin];
  }

  /**
   * 패스워드가 설정되어 있을 경우 패스워드 입력창에서 넘어오는 패스워드를 확인하고 session을 설정
   */
  public function _passwordConfirm($request, $tbl_name, $article)
  {

    $validator = Validator::make($request->all(), [
      'password' => 'required',
    ], [
      'password.required' => '패스워드를 입력해 주세요'
    ]);

    
    if (trim($request->password) !== trim($article->password)) {
      $validator->after(function($validator)
      {
        $validator->errors()->add('password', '일치하지 않은 패스워드입니다.');
      });

        return ['error'=>'validation', 'errors'=>$validator->errors()];
    } else {
      Cookie::queue(Cookie::make('pass-'.$tbl_name.$article->id, '1'));
      return ['error'=>false];
    }
  }

  public function _viewApi($tbl_name, $article, $request)
  {
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    $content = Articles::find($article);

    if ($request->cookie($tbl_name.$content->id) != '1') {
      $content->hit ++;
      $content->save();
    }

    Cookie::queue(Cookie::make($tbl_name.$content->id, '1'));
    return response()->json(['error'=>false, 'article' => $content], 200);//500, 203
  }
/*
  public function _comment($request, $tbl_name, $article)
  {
    $comment = $article->comment[0];
    $comment->content = htmlentities($comment->content);
    if($request->ajax()){
      return response()->json([$comment], 200);//500, 203
    }
  }
*/
    /*
     * Modify Form
     *
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function _edit($request, $tbl_name, $article)
  {
    $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    if ($article->isOwner(Auth::user()) || $isAdmin) {
      return ['error'=>false, 'article'=>$article, 'cfg'=>$cfg];
    } else {
      return ['error'=>'권한이 없습니다.'];
    }
  }

  /*
    * Delete Article
    * Step1 : delete files
    * Step2 : files table
    * Step3 : delete article
    * @param String $tbl_name
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function _destroy($request, $tbl_name, $article)
  {
    
    $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);


    if (!$article->isOwner(Auth::user()) && !$isAdmin) {
      return ['error'=>'삭제권한이 없습니다.'];
    }

    //1. delete files
    Storage::deleteDirectory('public/bbs/'.$cfg->id.'/'.date("Ym", strtotime($article->created_at)).'/'.$article->id);

    //2. delete files table
    //$article->files->delete();
    Files::where('bbs_articles_id', $article->id)->delete();

    //3. delete article
    $article->delete();
    return ['error'=>false];
  }

    /**
     * file download from storage
     */
  public function _download($file){
    //get file name
    // $file = Files::findOrFail($id);

    $file_path = storage_path() .'/app/'. $file->path_to_file;

    if (file_exists($file_path))
    {
      return ['error'=> false, 'file_path'=>$file_path, 'file' => $file];
    }
    else
    {
      return ['error'=>'Requested file does not exist on our server!'];
    }
  }

  /**
   * check Permission for Read, write
   * @param String $mode read/write
   * @param Object $cfg  Bbs Config
   * @return Boolean
   */
  /*
  private function permission($mode, $cfg){
    $rtn = false;
    switch($mode){
      case "read":
        if($cfg->auth_read == 'none')
          return true;
        else{

        }
      break;
      case "write":
        switch($cfg->auth_write){
          case "none":
            return true;
            break;
          case "login":
            if(Auth::check())
              return true;
            else
              return false;
            break;
          case "role":
            if(!Auth::check())
              return false;
            else

            break;
        }

      break;
    }
    return $rtn;
  }
  */
}
