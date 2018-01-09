<?php
namespace Pondol\Bbs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Auth;
use View;
use Cookie;
use File;
use Storage;
use Response;

use Pondol\Bbs\Models\Bbs_tables as Tables;
use Pondol\Bbs\Models\Bbs_articles as Articles;
use Pondol\Bbs\Models\Bbs_files as Files;
use Pondol\Bbs\BbsService;

class BbsController extends \App\Http\Controllers\Controller {

    protected $bbsSvc;
    protected $cfg;
    public function __construct(BbsService $bbsSvc) {
        $this->bbsSvc   = $bbsSvc;
    }

    /*
     * List Page
     *
     * @param String $tbl_name
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tbl_name)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        $list = Articles::where('bbs_table_id', $cfg->id)->orderBy('created_at', 'desc')->paginate($this->itemsPerPage);
        return view('bbs::templates.'.$cfg->skin.'.index')->with(compact('list', 'cfg'));
        
    }

    /*
     * Write Form Page
     *
     * @param String $tbl_name
     * @return \Illuminate\Http\Response
     */
    public function createForm($tbl_name)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        return view('bbs::templates.'.$cfg->skin.'.create')->with(compact('cfg', 'errors'));
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
        
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        $article = new Articles;
        $article->bbs_table_id = $cfg->id;
        $article->title = $request->get('title');
        $article->content = $request->get('content');

        if (Auth::check()) {
            $article->user_id = Auth::user()->id;
        } else {
            $article->user_id = 0;
        }
        $article->save();
        
        $date_Ym = date("Ym");
        $filepath = 'public/bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;
        if(is_array($request->file('uploads')))
            foreach ($request->file('uploads') as $index => $upload) {
                if ($upload == null) continue;
                
                //get file path (bbs/bbs_tables_id/YM/bbs_articles_id)
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
            
        $this->contents_update($article, $cfg->id, $date_Ym);
        return redirect()->route('bbs.show', [$tbl_name, $article->id]);
    }

    /*
     * Modify Article
     *
     * @param  \Illuminate\Http\Request  $request
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tbl_name, $id)
    {

        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        $article = Articles::findOrFail($id);

        if (!$article->isOwner(Auth::user())) {
            return redirect()->route('bbs.index', $tbl_name);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2|max:100',
            'content' => 'required',
            //'username' => 'required|unique:users|min:2|max:8',
        ]);
        
        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $article->title = $request->get('title');
        $article->content = $request->get('content');
        $article->save();

        // Upload Attached files
        $date_Ym = date("Ym", strtotime($article->created_at));//수정일경우 기존 데이타의 생성일을 기준으로 가져온다.
        $filepath = 'public/bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;

        if(is_array($request->file('uploads')))
            foreach ($request->file('uploads') as $index => $upload) {
                
                echo "index:".$index.PHP_EOL;
             
                if ($upload == null) continue;
    
                // Delete exist files
                if (($file = $article->files->where('rank', $index)->first())) {
                    Storage::delete($file->path_to_file);
                    $file->delete();
                }
                
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
            }

        $this->contents_update($article, $cfg->id, $date_Ym);
        return redirect()->route('bbs.show', [$tbl_name, $article->id]);
    }
    /**
     * 에디터에 이미지가 포함된 경우 이미지를 현재 아이템에 editor라는 폴더를 만들고 그곳에 모두 복사한다. 
     * 그리고 contents에 포함된 링크 주소고 변경하여 데이타를 업데이트 한다. 
     */
    private function contents_update($article, $table_id, $date_Ym){

        $sourceDir = storage_path() .'/app/public/bbs/tmp/editor/'. session()->getId();
        $destinationDir = storage_path() .'/app/public/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor';
        
        
        $article->content = str_replace('/storage/bbs/tmp/editor/'.session()->getId(), '/storage/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor', $article->content);
        
        $article->save();

        $success = File::copyDirectory($sourceDir, $destinationDir);
       Storage::deleteDirectory('public/bbs/tmp/editor/'. session()->getId());
    }
    /*
     * Show Article
     *
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $tbl_name, $id)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        $article = Articles::findOrFail($id);

        if ($request->cookie($tbl_name.$id) != '1') {
            $article->hit ++;
            $article->save();
        }

        Cookie::queue(Cookie::make($tbl_name.$id, '1'));
        return view('bbs::templates.'.$cfg->skin.'.show')->with(compact('article', 'cfg'));
    }

    /*
     * Modify Form
     *
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editForm($tbl_name, $id)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        
        $article = Articles::findOrFail($id);
        
        if (!$article->isOwner(Auth::user())) {
            return redirect()->route('bbs.index', $tbl_name);
        }
        
        return view('bbs::templates.'.$cfg->skin.'.create')->with(compact('article', 'cfg'));
        //return view('bbs::templates.'.$cfg->skin.'.create')->with(compact('article', 'cfg'));
    }

    /*
     * Delete Article
     * Step1 : delete files
     * Step2 : files table
     * Step3 : delete article
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($tbl_name, $id)
    {
        
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        $article = Articles::findOrFail($id);

        if (!$article->isOwner(Auth::user())) {
            return redirect()->route('bbs.index', $tbl_name);
        }
        //1. delete files 
        Storage::deleteDirectory('public/bbs/'.$cfg->id.'/'.date("Ym", strtotime($article->created_at)).'/'.$article->id);

        //2. delete files table
        //$article->files->delete();
        Files::where('bbs_articles_id', $article->id)->delete();

        //3. delete article
        $article->delete();

        return redirect()->route('bbs.index', $tbl_name);
    }
    
    /**
     * file download from storage
     */
    public function download($id){
        //get file name
        $file = Files::findOrFail($id);
        
        $file_path = storage_path() .'/app/'. $file->path_to_file;

        if (file_exists($file_path))
        {
            // Send Download
            return Response::download($file_path, $file->file_name, [
                'Content-Length: '. filesize($file_path)
            ]);
        }
        else
        {
            exit('Requested file does not exist on our server!');
        }
        
    }
}