<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Auth;
use Pondol\Bbs\BbsService;

class AdminController extends \Pondol\Bbs\AdminController
{

    // BBS Items per Page
    protected $itemsPerPage = 10;
   // protected $deaultUrlParams = array('blade_extends' =>'bbs.layouts.default');
    protected $deaultUrlParams = array('blade_extends' =>'admin.layouts.admin');
    
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
            if (Auth::check()) {
                if(!Auth::user()->hasRole('administrator'))
                    return redirect('');
            } else {
                return redirect('');
            }
            return $next($request);
        });
    }
}
