<?php
namespace Wangta69\Bbs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use View;
use Cookie;
use File;
use Storage;
use Response;
use Auth;

use Wangta69\Bbs\Models\BbsTables as Tables;
use Wangta69\Bbs\Models\BbsArticles as Articles;
use Wangta69\Bbs\Models\BbsComments as Comments;
use Wangta69\Bbs\Models\BbsFiles as Files;

use Pondol\Image\GetHttpImage;
use Wangta69\Bbs\BbsService;


class BbsExtendsController extends \App\Http\Controllers\Controller {

  protected $bbsSvc;
  protected $cfg;
  protected $laravel_ver;
  public function __construct() {
    $this->bbsSvc = \App::make('Wangta69\Bbs\BbsService');
    $laravel = app();
    $this->laravel_ver = $laravel::VERSION;
  }

  /*
   * List Page
   *
   * @param String $tbl_name
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request, $articles, $cfg)
  {
    $f = $request->input('f', null); // Searching Field ex) title, content
    $s = $request->input('s', null); // Searching text


    
    $user = $request->user();
    if ($cfg->auth_list === 'login' &&  !$user) {
      return ['error'=>'login'];
    }

    $articles->orderBy('order_num');

    if ($f && $s) {
      $articles = $articles->where($f, 'like', '%'.$s.'%');
    }

    $adminrole = config('bbs.admin_roles'); // administrator

    // 관리자 권한 및 본인에게만 데이타를 보여 준다.
    if ($cfg->enable_qna == '1') {
      $adminrole = config('bbs.admin_roles'); // administrator
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

    return $articles;
  }

  /**
   * index를 가져올 전처리 작업 (select 등 다양한 경우에 대비하기위해 index를 가져오기 전에 먼저 선 작업을 한다.)
   */
  public function preIndex($tbl_name) {
    $obj = new \stdClass();
    $obj->cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
    $obj->articles = Articles::where('bbs_table_id', $obj->cfg->id);
    return $obj;
  }

  /**
  * API 호출시 직접 데이타 처리
  */
  public function indexApi(Request $request, $tbl_name)
  {
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    $articles = Articles::where('bbs_table_id', $cfg->id)
      ->orderBy('order_num')
      ->paginate($cfg->lists)
      ->appends(request()->query());
    return response()->json(['articles' => $articles, 'cfg'=>$cfg], 200);//500, 203

  }

  /*
   * Write Form Page
   *
   * @param String $tbl_name
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request, $tbl_name)
  {

    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    //check permission
    $permission_result = $cfg->hasPermission('write');
    if(!$permission_result)
      abort(403, 'Unauthorized action.');

    return ['cfg'=>$cfg];
  }

    /*
     * Store to BBS
     *
     * @param String $tbl_name
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request, $tbl_name)
  {

    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    $validator = Validator::make($request->all(), [
      'title' => 'required|min:2|max:100',
      'content' => 'required',
    ], [
      'title.*' => '2글자 이상의 제목을 입력해 주세요',
      'content.required' => '내용을 입력해 주세요',
      'password.required' => '패스워드를 입력해 주세요'
    ]);

    $validator->sometimes('password', 'required', function ($input) use ($cfg) {
      return $cfg->enable_password == 1;
    });


    // if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator->errors());

    if ($validator->fails()) 
      return ['error'=>'validation', 'errors'=>$validator->errors()];

    $parent_id = $request->get('parent_id');//if this vaule setted it means reply

    //check permission
    $permission_result = $cfg->hasPermission('write');

    if(!$permission_result)
      abort(403, 'Unauthorized action.');


    $article = new Articles;

    $article->bbs_table_id = $cfg->id;
    $article->user_name = $request->get('user_name');

    if (Auth::check()) {
      $article->user_id = Auth::user()->id;
      $article->user_name = $article->user_name ? $article->user_name : Auth::user()->name;
    } else {
      $article->user_id = 0;
    }

    $article->order_num = $this->get_order_num();
    $article->parent_id = 0;//firt fill then update
    $article->comment_cnt = 0;
    $article->title = $request->get('title');
    $article->password = $request->get('password');
    $article->content = $request->get('content');
    $article->text_type = $request->input('text_type', 'br');

    $article->save();
    $article->parent_id = $parent_id ? $parent_id : $article->id;
    $article->save();

    $date_Ym = date("Ym");
    $filepath = 'public/bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;
    // $filepath = base_path().'/public/bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;
    if(is_array($request->file('uploads')))
      foreach ($request->file('uploads') as $index => $upload) {
        if ($upload == null) continue;

        //get file path (bbs/bbs_tables_id/YM/bbs_articles_id)
        //upload to storage
        $filename = $upload->getClientOriginalName();
        $fileextension = $upload->getClientOriginalExtension();

        $path=Storage::put($filepath,$upload); // //Storage::disk('local')->put($name,$file,'public');
        // $path = File::put($filepath , $upload);
        //save to database
        $file = new Files;
        $file->rank = $index;
        $file->bbs_articles_id = $article->id;
        $file->file_name = $filename;
        $file->path_to_file = $path;
        $file->name_on_disk = basename($path);
        $file->save();
      }//foreach if

    $this->contents_update($article, $cfg->id, $date_Ym);
    $this->set_representaion($article);
    return [$tbl_name, $article->id, $cfg];
  }

    /*
     * Modify Article
     *
     * @param  \Illuminate\Http\Request  $request
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function update(Request $request, $tbl_name, Articles $article)
  {
    $isAdmin = BbsService::hasRoles(config('bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    //check permission
    $permission_result = $cfg->hasPermission('write');
    if(!$permission_result)
      abort(403, 'Unauthorized action.');

    if (!$article->isOwner(Auth::user()) && !$isAdmin) {
      return redirect()->route('bbs.index', [$tbl_name]);
    }

    $validator = Validator::make($request->all(), [
      'title' => 'required|min:2|max:100',
      'content' => 'required',
        //'username' => 'required|unique:users|min:2|max:8',
    ]);

    if ($validator->fails()) 
      return redirect()->back()->withErrors($validator->errors());

    $article->title = $request->get('title');
    $article->content = $request->get('content');
    $article->save();

    // Upload Attached files
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
    // return [$tbl_name, $article->id];
    return [$tbl_name, $article->id, $cfg];
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

    $sourceDir = storage_path() .'/app/public/bbs/tmp/editor/'. session()->getId();
    $destinationDir = storage_path() .'/app/public/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor';

    $article->content = str_replace('/storage/bbs/tmp/editor/'.session()->getId(), '/storage/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor', $article->content);

    $article->save();

    $success = File::copyDirectory($sourceDir, $destinationDir);
    Storage::deleteDirectory('public/bbs/tmp/editor/'. session()->getId());
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
        Log::info('file->path_to_file:'.$file);
        $tmp = explode('.', $file->path_to_file);
        $extension = end($tmp);
        if(in_array($extension, $representaion_image_array)){
          $article->image = $file->path_to_file;
          Log::info('article->image:'.$file->path_to_file);
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
  public function show(Request $request, $tbl_name, Articles $article)
  {
    $isAdmin = BbsService::hasRoles(config('bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    // 시간되면 이 부분은 좀더 고도화 필요
    $user = $request->user();
    if ($cfg->auth_read === 'login' &&  !$user) {
      // return redirect()->route('login');
      return ['error'=>'login'];
    }

    if (!$isAdmin && $cfg->enable_password == 1 && $request->cookie('pass-'.$tbl_name.$article->id) != '1') {
      return ['error'=>'password'];
    }

    if ($request->cookie($tbl_name.$article->id) != '1') {
      $article->hit ++;
      $article->save();
    }

    Cookie::queue(Cookie::make($tbl_name.$article->id, '1'));
    if($request->ajax()){
      return response()->json([$article], 200);//500, 203
    }

    return  ['article' => $article, 'cfg'=>$cfg, 'isAdmin'=>$isAdmin];
  }

  public function passwordConfirm(Request $request, $tbl_name, Articles $article)
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

      if ($validator->fails()) 
        return ['error'=>'validation', 'errors'=>$validator->errors()];
    } else {
      Cookie::queue(Cookie::make('pass-'.$tbl_name.$article->id, '1'));
      return;
    }
  }

  public function viewApi($tbl_name, $article, Request $request)
  {
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    $content = Articles::find($article);

    if ($request->cookie($tbl_name.$content->id) != '1') {
      $content->hit ++;
      $content->save();
    }

    Cookie::queue(Cookie::make($tbl_name.$content->id, '1'));
    return response()->json(['article' => $content], 200);//500, 203
  }

  public function comment(Request $request, $tbl_name, Articles $article)
  {
    $comment = $article->comment[0];
    $comment->content = htmlentities($comment->content);
    if($request->ajax()){
      return response()->json([$comment], 200);//500, 203
    }
  }

    /*
     * Modify Form
     *
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function edit(Request $request, $tbl_name, Articles $article)
  {
    $isAdmin = BbsService::hasRoles(config('bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    if ($article->isOwner(Auth::user()) || $isAdmin) {
      return ['article'=>$article, 'cfg'=>$cfg];
    } else {
      return redirect()->route('bbs.index', [$tbl_name]);
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
  public function destroy(Request $request, $tbl_name, Articles $article)
  {
    $isAdmin = BbsService::hasRoles(config('bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

    if (!$article->isOwner(Auth::user()) && !$isAdmin) {
      return redirect()->route('bbs.index', [$tbl_name]);
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
  public function download($id){
    //get file name
    $file = Files::findOrFail($id);

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

  /**
   * @param String $file  public/bbs/5/201804/37/filename.jpeg
   */
  public static function get_thumb($file, $width=null, $height=null) {
    if ($file) {
      if($width == null &&  $height == null) {
        return str_replace(["public"], ["/storage"], $file);
      } else if($width == null ) {
        $width = $height;
      } else if($height == null) {
        $height = $width;
      }
      $name = substr($file, strrpos($file, '/') + 1);
      $thum_dir = substr($file, 0, -strlen($name)).$width."_".$height;
      // return $name;
      $thum_to_storage = storage_path() .'/app/'.$thum_dir;

      if(!file_exists($thum_to_storage."/".$name)){//thumbnail 이미지를 돌려준다.
        $file_to_storage = storage_path() .'/app/'.$file;
        $image = new GetHttpImage();

        try {
          // $image->read($file_to_storage)->set_size($width, $height)->copyimage()->save($thum_to_storage);
          $result = $image->read($file_to_storage)->set_size($width, $height)->copyimage();
          if ($result) {
              $result->save($thum_to_storage);
          }
        } catch (\Exception $e) {
        }
      }

      return str_replace(["public"], ["/storage"], $thum_dir)."/".$name;
    }else
      return '';
  }
}
