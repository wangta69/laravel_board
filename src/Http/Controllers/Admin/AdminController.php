<?php

namespace Pondol\Bbs\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\Models\BbsTables;
use Pondol\Bbs\Traits\AdminTrait;

class AdminController extends Controller
{
    use AdminTrait;

    public function __construct(BbsService $bbsSvc)
    {
        $this->bbsSvc = $bbsSvc;
    }

    public function index(Request $request)
    {
        $data = $this->_index($request); // [list, cfg]

        return view('bbs::admin.index', $data);
    }

    public function createForm(Request $request, BbsTables $table)
    {
        $data = $this->_createForm($request, $table); // [table, cfg, skins, skins_admin, roles]

        return view('bbs::admin.create', $data);
    }

    public function store(Request $request)
    {
        $result = $this->_store($request);
        if ($result->error == 'validator') {
            return redirect()->back()->withInput()->withErrors($result->validator->errors());
        } else {
            return redirect()->route('bbs.admin.index');
        }
    }

    public function update(Request $request, BbsTables $table)
    {
        $result = $this->_update($request, $table);

        if ($result->error == 'validator') {
            return redirect()->back()->withInput()->withErrors($result->validator->errors());
        } else {
            return redirect()->route('bbs.admin.index');
        }
    }

    public function configUpdate(Request $request)
    {
        $this->_configUpdate($request);

        return redirect()->route('bbs.admin.index', []);
    }

    // Tables -> BbsTables 로 타입힌트 정정
    public function show(Request $request, BbsTables $table)
    {
        $rtnObj = $this->_show($request, $table); // ['cfg', 'skins', 'roles']

        return view('bbs::admin.create', [
            'cfg' => $table,
            'skins' => $rtnObj->skins,
            'roles' => $rtnObj->roles,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->_destroy($id);

        if ($request->ajax()) {
            return response()->json(['result' => true, 'code' => '000'], 200);
        } else {
            return redirect()->route('bbs.admin.index', []);
        }
    }
}
