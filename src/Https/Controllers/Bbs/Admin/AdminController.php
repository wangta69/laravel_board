<?php
namespace App\Http\Controllers\Bbs\Admin;
use App\Http\Controllers\Controller;

use Auth;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\Traits\AdminTrait;
class AdminController extends Controller
{
  use AdminTrait;

  public function __construct(
    BbsService $bbsSvc 
  )
  {
    $this->bbsSvc = $bbsSvc;
    $this->middleware('auth');

    $this->middleware(function ($request, $next) {
      $value = config('bbs.admin_roles'); // administrator
      if (Auth::check()) {
        if(!$this->bbsSvc->hasRoles($value))
          return redirect('');
      } else {
        return redirect('');
      }
      return $next($request);
    });
  }
}
