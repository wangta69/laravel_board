<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Auth;
use Wangta69\Bbs\BbsService;

class AdminController extends \Wangta69\Bbs\AdminController
{

    // BBS Items per Page
    protected $itemsPerPage = 10;
    //protected $deaultUrlParams = array('blade_extends' =>'bbs.layouts.default');
   protected $deaultUrlParams = array('blade_extends' =>'bbs.admin.default-layout');

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BbsService $bbsSvc)
    {
        parent::__construct();
        $this->bbsSvc = $bbsSvc;


        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $value = config('bbs.admin_roles'); // administrator
            if (Auth::check()) {
                if(!BbsService::hasRoles($value))
                    return redirect('');
            } else {
                return redirect('');
            }
            return $next($request);
        });

    }
}
