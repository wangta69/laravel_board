<?php

namespace App\Http\Controllers\Bbs\Admin;

use Auth;

class CategoryController extends \Wangta69\Bbs\CategoryBaseController
{
    /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();

    $this->middleware('auth');
    // $this->itemsPerPage = 10; // change table list count;
    $this->middleware(function ($request, $next) {
      $value = config('bbs.admin_roles'); // administrator
      if (Auth::check()) {
        // if(!BbsService::hasRoles($value))
        if(!$this->bbsSvc->hasRoles($value))
          return redirect('');
      } else {
        return redirect('');
      }
      return $next($request);
    });
  }
}
