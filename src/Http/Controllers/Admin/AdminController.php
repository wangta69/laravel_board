<?php
namespace Pondol\Bbs\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;

use Pondol\Bbs\Models\BbsTables;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\Traits\AdminTrait;

use App\Http\Controllers\Controller;
class AdminController extends Controller
{
  use AdminTrait;

  public function __construct(
    BbsService $bbsSvc 
  )
  {
    $this->bbsSvc = $bbsSvc;
  }

  public function index(Request $request)
  {
    $data = $this->_index($request); //[list, cfg];
    return view('bbs::admin.index', $data);
  }

  public function createForm(Request $request, BbsTables $table)
  {
    $data = $this->_createForm($request, $table); //[table, cfg, skins, skins_admin,  roles];
    
    return view('bbs::admin.create', $data);
  }

  public function store(Request $request) {
    $result = $this->_store($request);
    if ($result->error == 'validator') {
      return redirect()->back()->withInput()->withErrors($result->validator->errors());
    } else {
      return redirect()->route('bbs.admin.index');
    }
  }

  public function update(Request $request, BbsTables $table) {
    $result = $this->_update($request, $table);

    if ($result->error == 'validator') {
      return redirect()->back()->withInput()->withErrors($result->validator->errors());
    } else {
      return redirect()->route('bbs.admin.index');
    }
  }

  public function configUpdate(Request $request) {
    $this->_configUpdate($request);
    return redirect()->route('bbs.admin.index', []);
  }

  public function show(Request $request, Tables $table)
  {
    $data = $this->_show($request, $table); //['cfg', 'skins's, 'roles']

    return view('bbs::admin.create', $data);
  }

  public function destroy(Request $request, $id)
  {
    $this->_destroy($id);

    if($request->ajax()){
      return response()->json(['result'=>true, "code"=>"000"], 200);
    }else{
      return redirect()->route('bbs.admin.index', []);
    }
  }
}
