<?php
namespace Pondol\Bbs;

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
// use Pondol\Bbs\BbsService;

class BbsController extends Controller {

  use BbsBase;
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
  public function _index(Request $request, $tbl_name) {
    $result =  $this->index($request, $tbl_name);

    if(!$result['error']) {
      return view('bbs.templates.'.$result['cfg']->skin.'.index', $result);
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
  public function _create(Request $request, $tbl_name) {
    $result =  $this->create($request, $tbl_name);
    return view('bbs.templates.'.$result['cfg']->skin.'.create', $result);
  }
  /*
  * Store to BBS
  *
  * @param String $tbl_name
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function _store(Request $request, $tbl_name) {
    $result =  $this->store($request, $tbl_name);
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
  public function _edit(Request $request, $tbl_name, Articles $article) {
    $result =  $this->edit($request, $tbl_name, $article);
    if($result['error']) {
      return redirect()->route('bbs.index', [$tbl_name]);
    }else {
      return view('bbs.templates.'.$result['cfg']->skin.'.create', $result);
    }
  }

  public function _update(Request $request, $tbl_name, Articles $article) {
    $result =  $this->update($request, $tbl_name, $article);
    // exit;
    return redirect()->route('bbs.show', [$result[0], $result[1]]);
      // return redirect()->route('bbs.templates.'.$result['cfg']->skin.'show', $result);
  }

  /*
  * Show Article
  *
  * @param String $tbl_name
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */

  public function _show(Request $request, $tbl_name, Articles $article) {
    $result =  $this->show($request, $tbl_name, $article);
    if ($result['error']) {
      return $this->errorHandle($result['error']);
    }
    else {
      return view('bbs.templates.'.$result['cfg']->skin.'.show', $result);
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
  public function destroy(Request $request, $tbl_name, Articles $article)
  {

    $isAdmin = BbsService::hasRoles(config('bbs.admin_roles'));
    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
    $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

    if (!$article->isOwner(Auth::user()) && !$isAdmin) {
      return redirect()->route('bbs.index', [$tbl_name, 'urlParams='.$urlParams->enc]);
    }
    //1. delete files
    Storage::deleteDirectory('public/bbs/'.$cfg->id.'/'.date("Ym", strtotime($article->created_at)).'/'.$article->id);

    //2. delete files table
    //$article->files->delete();
    Files::where('bbs_articles_id', $article->id)->delete();

    //3. delete article
    $article->delete();

    return redirect()->route('bbs.index', [$tbl_name, 'urlParams='.$urlParams->enc]);
  }

  /**
   * file download from storage
   */
  public function _download($file){
    $result = $this->download($file);
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
  public static function get_thumb($file, $width=0, $height=0){
    if ($file) {
      if($width == 0 &&  $height == 0)
        return str_replace(["public"], ["/storage"], $file);

      $name = substr($file, strrpos($file, '/') + 1);
      $thum_dir = substr($file, 0, -strlen($name)).$width."_".$height;
      // return $name;
      $thum_to_storage = storage_path() .'/app/'.$thum_dir;
      //home/Web/coinvill-web/storage/app/public/bbs/5/201804/37/205x205/Srrf1axuyM1ZO9NaYM3lStoNLZyVvAfEgWMqWNUU.jpeg
      //return $file;
      //return $thum_to_storage."/".$name;


      if(!file_exists($thum_to_storage."/".$name)){//thumbnail 이미지를 돌려준다.
        $file_to_storage = storage_path() .'/app/'.$file;
        $image = new GetHttpImage();
        $image->read($file_to_storage)->set_size($width, $height)->copyimage()->save($thum_to_storage);
      }

      return str_replace(["public"], ["/storage"], $thum_dir)."/".$name;
    }else
      return '';

  }

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
        return redirect()->route(config('bbs.route.login'));
        break;
    }


    
  }
}
