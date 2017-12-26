<?php
namespace Pondol\Bbs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Route;
use Auth;
use View;
use Cookie;
use File;
use Storage;
use Response;

use Pondol\Bbs\Models\Bbs_tables as Tables;
use Pondol\Bbs\Models\Bbs_articles as Articles;
use Pondol\Bbs\Models\Bbs_files as Files;

class BbsController extends \App\Http\Controllers\Controller {

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
	 * List Page
	 *
	 * @param String $tbl_name
	 * @return \Illuminate\Http\Response
	 */
    public function index($tbl_name)
    {

       
       $bbs_config = $this->get_table_info($tbl_name);
		$list = Articles::orderBy('created_at', 'desc')->paginate($this->itemsPerPage);

        return view('bbs.templates.basic.index')->with(compact('list', 'bbs_config', 'tbl_name'));
        
    }

	/*
	 * Write Form Page
	 *
	 * @param String $tbl_name
	 * @return \Illuminate\Http\Response
	 */
    public function create($tbl_name)
    {

        $bbs_config = $this->get_table_info($tbl_name);
        
       
        //$download_link = link_to_route('bbs.download', 'file/example.png', [1]);
       // echo $download_link;
        return view('bbs.templates.basic.create')->with(compact('tbl_name', 'bbs_config'));
    }

	/*
	 * Store to BBS
	 *
	 * @param String $tbl_name
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
    public function store(Request $request, $tbl_name)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2|max:100',
            'content' => 'required',
            //'username' => 'required|unique:users|min:2|max:8',
        ]);
        
        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        
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
        $date_Ym = date("Ym");
        if(is_array($request->file('uploads')))
    		foreach ($request->file('uploads') as $index => $upload) {
    			if ($upload == null) continue;
                
                //get file path (bbs/bbs_tables_id/YM/bbs_articles_id)
                $filepath = 'public/bbs/'.$bbs_config->id.'/'.$date_Ym.'/'.$article->id;
                //upload to storage
                $filename = $upload->getClientOriginalName();
                $path=Storage::put($filepath,$upload); // //Storage::disk('local')->put($name,$file,'public');
                
                //save to database
                $file = new Files;
                $file->rank = $index;
                $file->bbs_articles_id = $article->id;
                $file->file_name = $filename;
                $file->path_to_file = $path;
                $file->name_on_disk = basename($path);
                $file->save();
            }//foreach if
            
        $this->contents_update($article, $bbs_config->id, $date_Ym);
        return redirect()->route('bbs.show', [$tbl_name, $article->id]);
	}


    /**
     * 에디터에 이미지가 포함된 경우 이미지를 현재 아이템에 editor라는 폴더를 만들고 그곳에 모두 복사한다. 
     * 그리고 contents에 포함된 링크 주소고 변경하여 데이타를 업데이트 한다. 
     */
    private function contents_update($article, $table_id, $date_Ym){
        
        Log::info('contents_update start ');
        $sourceDir = storage_path() .'/app/public/bbs/tmp/editor/'. session()->getId();
        $destinationDir = storage_path() .'/app/public/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor';
        
        
        $article->content = str_replace('/storage/bbs/tmp/editor/'.session()->getId(), '/storage/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor', $article->content);
        
        echo $article->content;
        $article->save();
       // echo $sourceDir.PHP_EOL;
        //echo $destinationDir.PHP_EOL;
        
        //Storage::delete(str_replace('../storage/app/', '', $this->uploadPath.$file->filename));
        
        $success = File::copyDirectory($sourceDir, $destinationDir);
        //delete temp folder
      // $result = Storage::deleteDirectory($sourceDir);
       // exec('rm -r ' . $sourceDir);
       Storage::deleteDirectory('public/bbs/tmp/editor/'. session()->getId());
        //Log::info($result);
        //Storage::deleteDirectory('app/public/bbs/tmp/editor/'. session()->getId());
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

		if ($request->cookie($tbl_name.$id) != '1') {
			$article->hit ++;
			$article->save();
		}

		Cookie::queue(Cookie::make($tbl_name.$id, '1'));
        return view('bbs.templates.basic.show')->with(compact('article', 'tbl_name'));
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
		//$articles_model->setTable($this->board_setting->table_name);

		$article = $articles_model->findOrFail($id);
		if (!$article->isOwner(Auth::user())) {
			return redirect()->route('bbs.index', $tbl_name);
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
			return redirect()->route('bbs.index', $tbl_name);
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

		return redirect()->route('bbs.show', [$tbl_name, $article->id]);
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
			return redirect()->route('bbs.index', $tbl_name);
		}

		$article->delete();

		return redirect()->route('bbs.show', $tbl_name);
    }
    
    /**
     * file download from storage
     */
    public function download($id){
        //get file name
        $file = Files::findOrFail($id);
        
        $file_path = storage_path() .'/app/'. $file->path_to_file;
        
        echo $file_path;
        if (file_exists($file_path))
        {
            // Send Download
            return Response::download($file_path, $file->file_name, [
                'Content-Length: '. filesize($file_path)
            ]);
        }
        else
        {
            // Error
            exit('Requested file does not exist on our server!');
        }
        
    }
        /*
     * 파일명을 일정 규칙에 따라 리턴함
     *
     * @param UploadedFile
     * @return string
     
    protected function getFilename($file) {
        $filename = time();

        while (Storage::exists(str_replace('../storage/app/', '', $this->uploadPath.$filename.'.'.$file->getClientOriginalExtension()))) {
            $filename ++;
        }

        return $filename.'.'.$file->getClientOriginalExtension();
    }
    */
    /**
     * @param String $tbl_name
     * @return Tables 
     */
    private function get_table_info($tbl_name){
        
        $tables = new Tables;
        return $tables->get_config_by_tablename($tbl_name);
    }

}
