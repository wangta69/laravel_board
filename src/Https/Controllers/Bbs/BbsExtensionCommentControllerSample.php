<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Auth;
use Wangta69\Bbs\BbsService;

class BbsExtensionCommentControllerSample extends \Wangta69\Bbs\BbsExtendsCommentController
{


  // use BbsTraits;
    // BBS Items per Page
    // protected $itemsPerPage = 10;
    //protected $deaultUrlParams = array('blade_extends' =>'bbs.layouts.default');
    // protected $deaultUrlParams = array('blade_extends' =>'bbs.admin.default-layout');

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function _store(Request $request, $tbl_name, Articles $article, $comment_id) {
      $result =  $this->store($request, $tbl_name, $article, $comment_id);
      // return view('admin.bbs.'.$tbl_name.'.'.$article->id.'.show', $result);
      return redirect()->route('admin.bbs.show', [$tbl_name, $article->id]);
    }

    public function _update(Request $request, $tbl_name, Articles $article, Comments $comment) {
      $result =  $this->update($request, $tbl_name, $article, $comment);
      return \Response::json($result, 200);
    }

    public function _destroy(Request $request, $tbl_name, Articles $article, Comments $comment) {
      $result =  $this->destroy($request, $tbl_name, $article, $comment);
      return \Response::json($result, 200);
    }


}