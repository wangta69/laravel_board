<?php
namespace Pondol\Bbs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Response;
use Auth;

use Pondol\Bbs\Models\Bbs_tables as Tables;
use Pondol\Bbs\Models\Bbs_articles as Articles;
use Pondol\Bbs\Models\Bbs_comments as Comments;

class BbsCommentController extends \App\Http\Controllers\Controller {

    protected $bbsSvc;
    protected $cfg;
    public function __construct(BbsService $bbsSvc) {
        $this->bbsSvc   = $bbsSvc;
    }


/*
     * Store Comment to BBS
     *
     * @param String $tbl_name
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $tbl_name, $parent_id)
    {
        
        if(!$tbl_name || !$parent_id)
            abort(404, "Exception Message");

        $validator = Validator::make($request->all(), [
            'content' => 'required|min:2|max:255',
        ]);
        
        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        
        
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
       // $urlParams = $request->input('urlParams');
        
        $article = Articles::find($parent_id);
        

        $comment = new Comments;
    
        if (Auth::check()) {
            $comment->user_id = Auth::user()->id;
           // $comment->user_name = Auth::user()->id;
        } else {
            $comment->user_id = 0;
        }
        
        $comment->user_name = $request->get('user_name');
        $comment->bbs_articles_id = $article->id;//firt fill then update
        $comment->order_num = $this->get_order_num($article->id);
        $comment->content = $request->get('content');
        $comment->save();
        
        $article->comment_cnt++;
        $article->save();

        return redirect()->route('bbs.show', [$tbl_name, $article->id, 'urlParams='.$request->input('urlParams')]);
    }


    /*
     * 
     */
    private function get_order_num($article_id){
        $order_num = Comments::where('bbs_articles_id', $article_id)->min('order_num');
        return $order_num ? $order_num-1:-1;
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
    public function destroy(Request $request, $tbl_name, $id)
    {
        /*
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));
        $article = Articles::findOrFail($id);

        if (!$article->isOwner(Auth::user())) {
            return redirect()->route('bbs.index', [$tbl_name, 'urlParams='.$urlParams->enc]);
        }


        //3. delete article
        $article->delete();
*/
        return redirect()->route('bbs.index', [$tbl_name, 'urlParams='.$urlParams->enc]);
    }
    

}