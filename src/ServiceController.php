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

use Pondol\Image\GetHttpImage;
use Wangta69\Bbs\BbsService;


class ServiceController extends \App\Http\Controllers\Controller {

  protected $bbsSvc;
  protected $cfg;
  protected $laravel_ver;
  public function __construct() {
  }

  /*
   * List Page
   *
   * @param String $tbl_name
   * @return \Illuminate\Http\Response
   */
  public function routeUrl(Request $request)
  {
    return route($request->name, $request->params);
  }
}
