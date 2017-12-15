<?php
namespace Pondol\Bbs;

use Illuminate\Http\Request;

use Route;
use Auth;
use View;

use Pondol\Bbs\Models\Bbs_table as Tables;


    
    

class AdminController extends \App\Http\Controllers\Controller {

	
	public function __construct() {
		// 기본 라우트 이름을 저장한다.
		$routeArr = explode('.', Route::currentRouteName());
		array_pop($routeArr);
		
		$this->baseRouteName = implode('.', $routeArr);
		
		View::share('baseRouteName', $this->baseRouteName);
	}
	
	/*
	 * 게시판 리스트
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
		$list = Tables::orderBy('created_at', 'desc')->paginate($this->itemsPerPage);
        return view('bbs.admin.index')->with(compact('list'));
    }
    
	/*
	 * CREATE BBS
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function create()
    {
        return view('bbs.admin.create');
    }

	/*
	 * Delete BBS
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
    public function store(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'table_name' => 'required',
    		'skin' => 'required',
    	]);
		
		$model = new Tables;
		
		$model->name 		= $request->get('name');
		$model->table_name 	= $request->get('table_name');
		$model->skin		= $request->get('skin');
		$model->save();
		
        return redirect()->route('bbs.admin');
	}

	/*
	 * 게시판 보기
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function show($id)
    {
    	$model = new $this->model;
		
		$board = $model->findOrFail($id);
		
		return view('bbs.admin.show')->with(compact('board'));
    }

	/*
	 * 게시판 수정 뷰
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function edit($id)
    {
    }

	/*
	 * 게시판 수정
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function update(Request $request, $id)
    {
    }

	/*
	 * 게시판 삭제
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function destroy($id)
    {
		$board = Tables::findOrFail($id);
		$board->delete();
		return redirect()->route('bbs.admin');
    }
}
