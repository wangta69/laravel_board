<?php

namespace App\Http\Controllers\Bbs\Admin;

use Illuminate\Http\Request;
use Auth;
// use Wangta69\Bbs\BbsService;

class CategoryController extends \Wangta69\Bbs\CategoryController
{
    /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }
}
