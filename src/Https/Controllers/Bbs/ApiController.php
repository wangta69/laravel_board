<?php

namespace App\Http\Controllers\Bbs;

// use App\Http\Controllers\Controller;
use Pondol\Bbs\BbsController;
use Illuminate\Http\Request;
use Response;
use Validator;
use Auth;
use Illuminate\Support\Facades\Redis;
use Pondol\Bbs\Models\BbsTables as Tables;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsComments as Comments;
use Pondol\Bbs\Models\BbsFiles as Files;
use App\Notifications\CountChanged;
use Pondol\Bbs\BbsService;

class BbsApiController extends BbsController
{
    protected $bbsSvc;
    protected $deaultUrlParams;
    // protected $cfg;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct(BbsService $bbsSvc)
    // {
    //     //$this->middleware('guest', ['except' => 'logout']);
    //     $this->bbsSvc = $bbsSvc;
    // }

    /**
     * 리스트 가져오기
     */
    public function lists($tbl_name, Request $request)
    {
        $user = $request->user();
        $offset = $request->input('offset', 0);
        $take = $request->input('take', 10);

        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
    //    $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

        $articles = Articles::
            select('id', 'user_id', 'writer', 'title', 'image', 'hit', 'created_at')
            ->where('bbs_table_id', $cfg->id)
            ->skip($offset)
            ->take($take)
            ->orderBy('order_num');

        if ($tbl_name === 'qna') {
            $articles = $articles->where('user_id', $user->id);
        }

        $articles = $articles->get();
        return response()->json([
            'error'=>false,
            'articles' => $articles,
            'cfg'=>$cfg
        ], 200);//500, 203


        // $user = $request->user();
        // $items = Qna::where('user_id', $user->id)
        //     ->skip($offset)
        //     ->take($take)
        //     ->orderby('id', 'desc')
        //     ->get();
        // return response()->json([
        //     'error' => false,
        //     'items' => $items
        // ], 200);
    }

    /**
     * 수정하기
     */
    // public function update(Qna $qna, Request $request)
    // {
    //     $user = $request->user();
    //
    //     if ($qna->user_id != $user->id) {
    //         return response()->json([
    //             'error' => 'NOT_ALLOWED_ACCESS'
    //         ], 203);
    //     }
    //     $rules = [
    //         'item' => 'required|min:2',
    //         'subject' => 'required|min:2',
    //         'question' => 'required|min:2',
    //     ];
    //
    //     $messages = [
    //         'item.required' =>'SOME_FIELD_REQUIRED',
    //         'subject.required' =>'SOME_FIELD_REQUIRED',
    //         'question.required' =>'SOME_FIELD_REQUIRED',
    //     ];
    //
    //     $validator = Validator::make($request->all(), $rules, $messages);
    //
    //     if ($validator->fails()) {
    //         return response()->json(['error'=>$validator->errors()->first()], 203);
    //     } else {
    //         $qna->item = $request->item;
    //         $qna->subject = $request->subject;
    //         $qna->question = $request->question;
    //         $qna->save();
    //     }
    //
    //     return response()->json([
    //         'error' => false
    //     ], 200);
    // }

    /**
     * 보기
     */
    public function show(Request $request, $tbl_name, Articles $article)
    {
        $user = $request->user();

    //    $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
    //    $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

//        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
    //    $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));
//exit;
        // $content = Articles::find($article);
        if ($tbl_name === 'qna' &&  $article->user_id != $user->id) {
            return response()->json([
               'error' => 'NOT_ALLOWED_ACCESS'
            ], 203);
        }

        if ($request->cookie($tbl_name.$article->id) != '1') {
            $article->hit ++;
            $article->save();
        }

    //    Cookie::queue(Cookie::make($tbl_name.$content->id, '1'));
        return response()->json(['error'=>false, 'article' => $article], 200);//500, 203

        //
        // $user = $request->user();
        //
        // if ($qna->user_id != $user->id) {
        //     return response()->json([
        //         'error' => 'NOT_ALLOWED_ACCESS'
        //     ], 203);
        // }
        //
        // return response()->json([
        //     'error' => false,
        //     'item' => $qna
        // ], 200);
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

        $parent_id = $request->get('parent_id');//if this vaule setted it means reply

        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));


        //check permission
        $permission_result = $cfg->hasPermission('write');
        if(!$permission_result)
            abort(403, 'Unauthorized action.');


        $article = new Articles;

        $article->bbs_table_id = $cfg->id;
        $article->writer = $request->get('writer');

        if (Auth::check()) {
            $article->user_id = Auth::user()->id;
            $article->writer = $article->writer ? $article->writer : Auth::user()->name;
        } else {
            $article->user_id = 0;
        }

        $article->order_num = $this->get_order_num();
        $article->parent_id = 0;//firt fill then update
        $article->comment_cnt = 0;
        $article->title = $request->get('title');
        $article->content = $request->get('content');
        $article->text_type = $request->input('text_type', 'br');

        $article->save();


        $article->parent_id = $parent_id ? $parent_id : $article->id;
        $article->save();

        $date_Ym = date("Ym");
        $filepath = 'public/bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;

        if(is_array($request->file('uploads')))
            foreach ($request->file('uploads') as $index => $upload) {
                if ($upload == null) continue;

                //get file path (bbs/bbs_tables_id/YM/bbs_articles_id)
                //upload to storage
                $filename = $upload->getClientOriginalName();
                $fileextension = $upload->getClientOriginalExtension();

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
        $this->set_representaion($article);

        return response()->json(['error'=>false], 200);
        // return redirect()->route('bbs.show', [$tbl_name, $article->id, 'urlParams='.$urlParams->enc]);
    }

    /**
     * 저장하기
     */
    // public function store(Request $request)
    // {
    //
    //     $user = $request->user();
    //     $rules = [
    //         'item' => 'required|min:2',
    //         'subject' => 'required|min:2',
    //         'question' => 'required|min:2',
    //     ];
    //
    //     $messages = [
    //         'item.required' =>'SOME_FIELD_REQUIRED',
    //         'subject.required' =>'SOME_FIELD_REQUIRED',
    //         'question.required' =>'SOME_FIELD_REQUIRED',
    //     ];
    //
    //     $validator = Validator::make($request->all(), $rules, $messages);
    //
    //     if ($validator->fails()) {
    //         return response()->json(['error'=>$validator->errors()->first()], 203);
    //     } else {
    //         $qna = new Qna();
    //         $qna->user_id = $user->id;
    //         $qna->trade_id = $request->trade_id;
    //         $qna->ad_id = $request->ad_id;
    //         $qna->item = $request->item;
    //         $qna->subject = $request->subject;
    //         $qna->question = $request->question;
    //         $qna->save();
    //
    //         $qna->notify(new CountChanged('add', 'qnas'));
    //     }
    //
    //     return Response::json(['result'=>true], 200);
    // }

}
