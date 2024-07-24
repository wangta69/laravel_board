<?php
namespace App\Http\Controllers\Bbs\Admin;
use App\Http\Controllers\Controller;

use Auth;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\CategoryBase;
class CategoryController extends Controller
{

  use CategoryBase;
    /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(
    BbsService $bbsSvc 
  )
  {
    // parent::__construct();
    $this->bbsSvc = $bbsSvc;
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
