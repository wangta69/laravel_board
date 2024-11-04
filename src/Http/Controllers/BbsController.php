<?php
namespace Pondol\Bbs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use View;
use Cookie;
use File;
use Storage;
use Response;
use Auth;
use App\Http\Controllers\Controller;

use Pondol\Bbs\Models\BbsTables as Tables;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsComments as Comments;
use Pondol\Bbs\Models\BbsFiles as Files;

use Pondol\Image\GetHttpImage;
use Pondol\Bbs\Traits\BbsTrait;
use Pondol\Bbs\BbsService;

class BbsController extends Controller {

  use BbsTrait;
  protected $bbsSvc;
  protected $cfg;
  protected $laravel_ver;
  public function __construct(BbsService $bbsSvc) {
    $this->bbsSvc = $bbsSvc;
    $laravel = app();
    $this->laravel_ver = $laravel::VERSION;
  }


  /*
  * List Page
  *
  * @param String $tbl_name
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request, $tbl_name) {
    $result =  $this->_index($request, $tbl_name);

    if(!$result['error']) {
      return view('bbs.templates.user.'.$result['cfg']->skin.'.index', $result);
    } else {
      return $this->errorHandle($result['error']);
    }
  }

    /*
     * Write Form Page
     *
     * @param String $tbl_name
     * @return \Illuminate\Http\Response
     */
  public function create(Request $request, $tbl_name) {
    $result =  $this->_create($request, $tbl_name);
    return view('bbs.templates.user.'.$result['cfg']->skin.'.create', $result);
  }
  /*
  * Store to BBS
  *
  * @param String $tbl_name
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request, $tbl_name) {
    $result =  $this->_store($request, $tbl_name);
    if(isset($result['error'])) {
      return $this->errorHandle($result['error']);

    }
    // return redirect()->route('admin.bbs.show', [$result[0], $result[1]]);
    return redirect()->route('bbs.show', [$result[0], $result[1]]);
  }

  /*
  * Modify Form
  * @param String $tbl_name
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit(Request $request, $tbl_name, Articles $article) {
    $result =  $this->_edit($request, $tbl_name, $article);
    if($result['error']) {
      return redirect()->route('bbs.index', [$tbl_name]);
    }else {
      return view('bbs.templates.user.'.$result['cfg']->skin.'.create', $result);
    }
  }

  public function update(Request $request, $tbl_name, Articles $article) {

    return;
    $result =  $this->_update($request, $tbl_name, $article);
    // exit;
    return redirect()->route('bbs.show', [$result[0], $result[1]]);
  }

  public function destroy(Request $request, $tbl_name, Articles $article) {
    
    $result =  $this->_destroy($request, $tbl_name, $article);

    if($request->ajax()){
      return response()->json($result, 200);//500, 203
    } else {
      return redirect()->route('bbs.index', [$tbl_name]);
    }
  }

  /*
  * Show Article
  *
  * @param String $tbl_name
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */

  public function show(Request $request, $tbl_name, Articles $article) {
    $result =  $this->_show($request, $tbl_name, $article);
    if ($result['error']) {
      return $this->errorHandle($result['error']);
    }
    else {
      return view('bbs.templates.user.'.$result['cfg']->skin.'.show', $result);
    }
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
    * Delete Article
    * Step1 : delete files
    * Step2 : files table
    * Step3 : delete article
    * @param String $tbl_name
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  // public function destroy(Request $request, $tbl_name, Articles $article)
  // {

  //   $result =  $this->destroy($request, $tbl_name, $article);
    
  //   if($request->ajax()){
  //     return response()->json($result, 200);//500, 203
  //   } else {
  //     return redirect()->route('bbs.index', [$tbl_name, 'urlParams='.$urlParams->enc]);
  //   }
  // }

  /**
   * file download from storage
   */
  public function download(Files $file){
    $result = $this->_download($file);
    if ($result['error']) {
      return $this->errorHandle($result['error']);
    } else {
      return Response::download($result['file_path'], $result['file']->file_name, [
        'Content-Length: '. filesize($result['file_path'],)
      ]);
    }
  }

  public function deletFile(Request $request, Files $file) {
    Storage::delete($file->path_to_file);
    $file->delete();
    return response()->json(['error'=>false, 'file' => $file], 200);
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
   * 썸내일 생성용
   * @param String $file  public/bbs/5/201804/37/filename.jpeg
   */
  // public static function get_thumb($file, $width=0, $height=0){
  //   if ($file) {
  //     if($width == 0 &&  $height == 0)
  //       return str_replace(["public"], ["/storage"], $file);

  //     $name = substr($file, strrpos($file, '/') + 1);
  //     $thum_dir = substr($file, 0, -strlen($name)).$width."_".$height;
  //     // return $name;
  //     $thum_to_storage = storage_path() .'/app/'.$thum_dir;
  //     //home/Web/coinvill-web/storage/app/public/bbs/5/201804/37/205x205/Srrf1axuyM1ZO9NaYM3lStoNLZyVvAfEgWMqWNUU.jpeg
  //     //return $file;
  //     //return $thum_to_storage."/".$name;


  //     if(!file_exists($thum_to_storage."/".$name)){//thumbnail 이미지를 돌려준다.
  //       $file_to_storage = storage_path() .'/app/'.$file;
  //       $image = new GetHttpImage();
  //       $image->read($file_to_storage)->set_size($width, $height)->copyimage()->save($thum_to_storage);
  //     }

  //     return str_replace(["public"], ["/storage"], $thum_dir)."/".$name;
  //   }else
  //     return '';

  // }

  /**
   * 이미지 리사이징
   * @param String $file  public/bbs/5/201804/37/filename.jpeg
   */
  public static function resizeImage($file, $width=0, $height=0) {

    // echo 'width:'.$width.', height:'.$height.PHP_EOL;
    if ($file) {
        if($width == null &&  $height == null)
          return str_replace(["public"], ["/storage"], $file);

        $name = substr($file, strrpos($file, '/') + 1);
        $thum_dir = substr($file, 0, -strlen($name)).$width."_".$height;
        // return $name;
        $thum_to_storage = storage_path() .'/app/'.$thum_dir;

        if(!file_exists($thum_to_storage."/".$name)){//thumbnail 이미지를 돌려준다.
          $file_to_storage = storage_path() .'/app/'.$file;
          $image = new GetHttpImage();

          try {
            // $image->read($file_to_storage)->set_size($width, $height)->copyimage()->save($thum_to_storage);
            
            $result = $image->read($file_to_storage)->resize($width, $height)->copyimage2();
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



  private function errorHandle($error) {
    switch($error) {
      case 'validation':
        return redirect()->back()->withInput()->withErrors($result['errors']);
        break;
      case 'login':
        return redirect()->route(config('pondol-bbs.route.login'));
        break;
    }
  }

  public function preIndex($tbl_name) {
    return $this->_preIndex($tbl_name);
  }
}
