<?php

namespace App\Http\Controllers\Bbs\Admin;

use Illuminate\Http\Request;
use Auth;
use Wangta69\Bbs\BbsService;

class AdminController extends \Wangta69\Bbs\AdminController
{

    protected $itemsPerPage = 10;

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
