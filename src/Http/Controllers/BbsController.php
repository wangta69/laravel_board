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

use Pondol\Meta\Facades\Meta;
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
    $result =  $this->_index($request, $tbl_name); // ['error', 'articles', 'top_articles', 'cfg']
    $meta = Meta::get()->suffix(function($suffix) use($result){
      $suffix->title = $result['cfg']->name;
    });
    if(!$result['error']) {
      $result['meta']= $meta;
      return view('bbs.templates.user.'.$result['cfg']->skin.'.index', $result);
    } else {
      return $this->errorHandle($result);
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

    if($result['error']) {
      return $this->errorHandle($result);
    }
    // return redirect()->route('admin.bbs.show', [$result[0], $result[1]]);
    return redirect()->route('bbs.show', [$result['tbl_name'], $result['article']->id]);
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

    $result =  $this->_update($request, $tbl_name, $article);
    if($result['error']) {
      switch($result['error']) {
        case 'login': 
          return redirect()->route('login');
          break;
        case 'NotAuthenticated': 
          return redirect()->back()->withErrors(['NotAuthenticated'=>'Not Authenticated']);
          break;
        case 'validation': 
          return redirect()->back()->withErrors($result['errors']);
          break;
      }
    } else {
      return redirect()->route('bbs.show', [$result['tbl_name'], $result['article']->id]);
    }
    
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
    $result =  $this->_show($request, $tbl_name, $article); // ['error', 'article', 'cfg', 'isAdmin'];
    $meta = Meta::get()->title($article->title)->keywords($article->keywords)->image(\Storage::url($article->image));
    if ($result['error']) {
      return $this->errorHandle($result);
    } else {
      if($request->ajax()){
        return response()->json([$result['article']], 200);//500, 203
      } else {
      $result['meta'] = $meta;
      return view('bbs.templates.user.'.$result['cfg']->skin.'.show', $result);
      }
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
      return $this->errorHandle($result);
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


  private function errorHandle($result) {
    switch($result['error']) {
      case 'validation':
        return redirect()->back()->withInput()->withErrors($result['errors']);
        break;
      case 'login':
        return redirect()->route(config('pondol-bbs.login_route_name'));
        break;
    }
  }

  // public function preIndex($tbl_name) {
  //   return $this->_preIndex($tbl_name);
  // }
}
