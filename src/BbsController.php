<?php
namespace Pondol\Bbs;

use Illuminate\Http\Request;

use Route;
use Auth;
use View;
use Cookie;
use Storage;

use Pondol\Bbs\Models\Bbs_tables as Tables;
use Pondol\Bbs\Models\Bbs_articles as Articles;

class BbsController extends \App\Http\Controllers\Controller {
	// 게시판 설정 테이블 모델
	protected $config_model = '';

	// 기본 라우트 이름
	private $baseRouteName = "";

	// 게시판 설정
	private $board_setting;

	public function __construct() {
	    

        
		// 기본 라우트 이름을 저장한다.
		/*
		$routeArr = explode('.', Route::currentRouteName());
		array_pop($routeArr);

		$this->baseRouteName = implode('.', $routeArr);

		// 게시판 설정 로드
		if ($this->config_model == '') abort('500');
		$config_model = new $this->config_model;
        */
/*
		if (Route::current() != null) {
			$tbl_name = Route::current()->parameters()['bo_id'];
			$this->board_setting = Config::findOrFail($tbl_name);

			// 첨부파일 업로드 경로 변경
			$this->uploadPath .= $tbl_name.'/';

			View::share('baseRouteName', $this->baseRouteName);
			View::share('bo_id', $tbl_name);
			View::share('board_setting', $this->board_setting);
		}
 * */
	}



	/*
	 * 게시글 리스트
	 *
	 * @param String $tbl_name
	 * @return \Illuminate\Http\Response
	 */
    public function index($tbl_name)
    {

       
       $bbs_config = $this->get_table_info($tbl_name);
        /*
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);
*/
		$list = Articles::orderBy('created_at', 'desc')->paginate($this->itemsPerPage);
        
        print_r($list);
        
        return view('bbs.templates.basic.index')->with(compact('list', 'bbs_config', 'tbl_name'));
    	//return view($this->board_setting->skin.'.index')->with(compact('list'));
        
    }

	/*
	 * 게시글 생성 뷰
	 *
	 * @param String $tbl_name
	 * @return \Illuminate\Http\Response
	 */
    public function create($tbl_name)
    {

        $bbs_config = $this->get_table_info($tbl_name);

        
        return view('bbs.templates.basic.create')->with(compact('tbl_name', 'bbs_config'));
    	//return view($this->board_setting->skin.'.create');
    }

	/*
	 * 게시글 생성
	 *
	 * @param String $tbl_name
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
    public function store(Request $request, $tbl_name)
    {
    	$this->validate($request, [
    		'title' => 'required',
    		'content' => 'required',
    	]);
        
        
        $bbs_config = $this->get_table_info($tbl_name);

//print_r($request);

		$article = new Articles;
        
		$article->bbs_table_id = $bbs_config->id;

		$article->title = $request->get('title');
		$article->content = $request->get('content');
        
		if (Auth::check()) {
			$article->user_id = Auth::user()->id;
		} else {
			$article->user_id = 0;
		}
        
		$article->save();

        if(is_array($request->file('uploads')))
    		foreach ($request->file('uploads') as $index => $upload) {
    			if ($upload == null) continue;
    
    			$filename = $this->getFilename($upload);
    			$upload->move($this->uploadPath, $filename);
    
    			$article_file = new $this->article_files_model;
    			$article_file->setTable($this->board_setting->table_name.'_files');
    			$article_file->rank = $index;
    			$article_file->articles_id = $articles_model->id;
    			$article_file->filename = $filename;
    			$article_file->save();
    		}
        return redirect()->route('bbs.show', [$tbl_name, $article->id]);
		//return redirect()->route($this->baseRouteName.'.show', [$tbl_name, $articles_model->id]);
	}

	/*
	 * 게시글 보기
	 *
	 * @param String $tbl_name
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function show(Request $request, $tbl_name, $id)
    {
    	$article = Articles::findOrFail($id);
		//$article->setTable($this->board_setting->table_name);

		//$article = $articles_model->findOrFail($id);
		//$article->setTable($this->board_setting->table_name);

		if ($request->cookie($tbl_name.$id) != '1') {
			$article->hit ++;
			$article->save();
		}

		Cookie::queue(Cookie::make($tbl_name.$id, '1'));
        return view('bbs.templates.basic.show')->with(compact('article', 'tbl_name'));
		//return view($this->board_setting->skin.'.show')->with(compact('article'));
    }

	/*
	 * 게시글 수정 뷰
	 *
	 * @param String $tbl_name
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function edit($tbl_name, $id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);

		$article = $articles_model->findOrFail($id);
		if (!$article->isOwner(Auth::user())) {
			return redirect()->route($this->baseRouteName.'.index', $tbl_name);
		}

		return view($this->board_setting->skin.'.create')->with(compact('article'));
    }

	/*
	 * 게시글 수정
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function update(Request $request, $tbl_name, $id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);

		$article = $articles_model->findOrFail($id);
		$article->setTable($this->board_setting->table_name);

		if (!$article->isOwner(Auth::user())) {
			return redirect()->route($this->baseRouteName.'.index', $tbl_name);
		}

		$this->validate($request, [
			'title' => 'required',
			'content' => 'required',
		]);

		$article->title = $request->get('title');
		$article->content = $request->get('content');
		$article->save();

		// 첨부파일 업로드
		foreach ($request->file('uploads') as $index => $upload) {
			if ($upload == null) continue;

			// 기존 파일 삭제
			if (($file = $article->files->where('rank', $index)->first())) {
				Storage::delete(str_replace('../storage/app/', '', $this->uploadPath.$file->filename));
				$file->setTable($this->board_setting->table_name.'_files');
				$file->delete();
			}

			$filename = $this->getFilename($upload);
			$upload->move($this->uploadPath, $filename);

			$article_file = new $this->article_files_model;
			$article_file->setTable($this->board_setting->table_name.'_files');
			$article_file->rank = $index;
			$article_file->articles_id = $article->id;
			$article_file->filename = $filename;
			$article_file->save();
		}

		return redirect()->route($this->baseRouteName.'.show', [$tbl_name, $article->id]);
    }

	/*
	 * 게시글 삭제
	 *
	 * @param String $tbl_name
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function destroy($tbl_name, $id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);

		$article = $articles_model->findOrFail($id);
		$article->setTable($this->board_setting->table_name);

		if (!$article->isOwner(Auth::user())) {
			return redirect()->route($this->baseRouteName.'.index', $tbl_name);
		}

		$article->delete();

		return redirect()->route($this->baseRouteName.'.show', $tbl_name);
    }
    
    
        /*
     * 파일명을 일정 규칙에 따라 리턴함
     *
     * @param UploadedFile
     * @return string
     */
    protected function getFilename($file) {
        $filename = time();

        while (Storage::exists(str_replace('../storage/app/', '', $this->uploadPath.$filename.'.'.$file->getClientOriginalExtension()))) {
            $filename ++;
        }

        return $filename.'.'.$file->getClientOriginalExtension();
    }
    
    /**
     * @param String $tbl_name
     * @return Tables 
     */
    private function get_table_info($tbl_name){
        
        $tables = new Tables;
        return $tables->get_config_by_tablename($tbl_name);
    }

}
