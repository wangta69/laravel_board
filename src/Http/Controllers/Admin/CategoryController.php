<?php
namespace Pondol\Bbs\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Auth;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\Traits\CategoryTrait;
class CategoryController extends Controller
{

  use CategoryTrait;
    /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(
    BbsService $bbsSvc 
  )
  {

    $this->bbsSvc = $bbsSvc;
    // $this->middleware('auth');

    // $this->middleware(function ($request, $next) {
    //   $value = config('pondol-bbs.admin_roles'); // administrator
    //   if (Auth::check()) {
    //     if(!$this->bbsSvc->hasRoles($value))
    //       return redirect('');
    //   } else {
    //     return redirect('');
    //   }
    //   return $next($request);
    // });
  }

  public function addCategory($tableId, Request $request) {
    $result = $this->_addCategory($request, $tableId); // [error, id]
    return response()->json($result, 200);
  }

  public function deleteCategory($category) {
    $result = $this->_deleteCategory($category); // [error]
    return response()->json($result, 200);
  }

  public function updateOrder($category, $direction) {
    $result = $this->_updateOrder($category, $direction); // [error]
    return response()->json($result, 200);
  }
}
