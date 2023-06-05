<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use Auth;
use Wangta69\Bbs\BbsService;

class BbsExtensionControllerSample extends \Wangta69\Bbs\BbsExtendsController
{


    // use BbsTraits;
      // BBS Items per Page
      // protected $itemsPerPage = 10;
      // //protected $deaultUrlParams = array('blade_extends' =>'bbs.layouts.default');
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
  
      public function _index(Request $request, $tbl_name) {
        $result =  $this->index($request, $tbl_name);
  
  
        if(isset($result['error'])) {
          if ($result['error'] == 'login') {
            return redirect()->route('login');
          }
        }
        return view('pages.ko.bbs.'.$tbl_name.'.index', $result);
      }
  
      public function _show(Request $request, $tbl_name, Articles $article) {
        $result =  $this->show($request, $tbl_name, $article);
        if(isset($result['error'])) {
            if ($result['error'] == 'password') {
              return view('pages.ko.bbs.'.$tbl_name.'.password-confirm', ['tbl_name'=>$tbl_name, 'article'=>$article->id]);
            }
        }
        return view('pages.ko.bbs.'.$tbl_name.'.show', $result);
      }
  
      public function _create(Request $request, $tbl_name) {
        $result =  $this->create($request, $tbl_name);
        return view('pages.ko.bbs.'.$tbl_name.'.create', $result);
      }
  
      public function _store(Request $request, $tbl_name) {
  
        // if ($validator->fails()) return ['error'=>'validation', 'errors'=>$validator->errors()];
        $result =  $this->store($request, $tbl_name);
        if(isset($result['error'])) {
          if ($result['error'] == 'validation') {
            return redirect()->back()->withInput()->withErrors($result['errors']);
          }
        }
        // return redirect()->route('pages.ko.bbs.show', $result);
        // return view('pages.ko.bbs.'.$tbl_name.'.show', $result);
        // Route::get('bbs/{tbl_name}/{article}/show', 'BBSController@_show')->name('bbs.show');
  
        $enable_password = $result[2]->enable_password;
        if ($enable_password) {
          return redirect()->route('bbs.index', [$result[0]]);
        } else {
          return redirect()->route('bbs.show', [$result[0], $result[1]]);
        }
        
      }
  
  
      public function _edit(Request $request, $tbl_name, Articles $article) {
        $result =  $this->edit($request, $tbl_name, $article);
        return view('pages.ko.bbs.'.$tbl_name.'.create', $result);
      }
  
      public function _update(Request $request, $tbl_name, Articles $article) {
        $result =  $this->update($request, $tbl_name, $article);
        return redirect()->route('pages.ko.bbs.show', $result);
      }
  
      public function _destroy(Request $request, $tbl_name, Articles $article) {
        $result =  $this->destroy($request, $tbl_name, $article);
        return redirect()->route('pages.ko.bbs.index', [$tbl_name]);
        // return view('admin.bbs.'.$tbl_name.'.create', $result);
      }
  
  
      // public function _passwordConfirm(Request $request, $tbl_name, Articles $article) {
      //   echo "=======================================";
      //   print_r($tbl_name);
      //   return view('pages.ko.bbs.'.$tbl_name.'.password-confirm', ['tbl_name'=>$tbl_name, 'article'=>$article->id]);
      // }
  
      public function _passwordConfirm(Request $request, $tbl_name, Articles $article) {
  
   
        $result =  $this->passwordConfirm($request, $tbl_name, $article);
  
        if(isset($result['error'])) {
          if ($result['error'] == 'validation') {
            return redirect()->back()->withInput()->withErrors($result['errors']);
          }
        }
  
        return redirect()->route('bbs.show', [$tbl_name, $article->id]);
        // return view('pages.ko.bbs.'.$tbl_name.'.password-confirm', $result);
      }
  }
  