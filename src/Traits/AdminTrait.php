<?php
namespace Pondol\Bbs\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Route;
use View;
use Validator;

use Pondol\Bbs\Models\BbsTables as Tables;
use Pondol\Auth\Models\Role\Role;
use Pondol\Bbs\Models\BbsConfig;

trait AdminTrait {
  protected $cfg;
  protected $itemsPerPage = 10;

  /*
   * BBS Tables List
   *
   * @return \Illuminate\Http\Response
   */
  public function _index(Request $request)
  {

    // 등록된 게시판 리스트 불러오기
    $list = Tables::orderBy('created_at', 'desc')->paginate($this->itemsPerPage);

    $cfg = $this->admin_extends();

    return ['list' => $list, 'cfg' => $cfg];
  }

  /*
   * BBS CREATE | EDIT Form
   *
   * @return \Illuminate\Http\Response
   */
  public function _createForm($request, $table)
  {
    // front 용
    $skin_dir =  resource_path('views/bbs/templates/user');
    $tmp_skins = array_map('basename',\File::directories($skin_dir));
    //키를 text로 변경
    foreach($tmp_skins as $v){
      $skins[$v] = $v;
    }

    // 관리자용
    $skin_dir =  resource_path('views/bbs/templates/admin');
    $tmp_skins = array_map('basename',\File::directories($skin_dir));
    //키를 text로 변경
    foreach($tmp_skins as $v){
      $skins_admin[$v] = $v;
    }

    // set default value;
    $table->lists = $table->lists ?? 10;
    $table->extends = $table->extends ?? 'bbs::layouts.default';
    $table->section = $table->section ?? 'content';

    $cfg = $this->admin_extends();
    return [
      'table'=>$table,
      'cfg'=>$cfg,
      'skins' => $skins,
      'skins_admin' => $skins_admin,
      'roles' => Role::get(),
    ];
  }

    /*
     * Create BBS
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function _store($request)
  {

    $obj = new \stdClass;
    $obj->error = false;

    $reserved_table_name = ['admin', 'root'];

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'table_name' => 'required|unique:bbs_tables',
      'skin' => 'required',
      'lists' => 'required',
      'section' => 'required',
      'extends' => 'required'
      //'username' => 'required|unique:users|min:2|max:8',
    ]);

    $validator->after(function($validator) use ($request, $reserved_table_name)
    {
      if (in_array($request->input('table_name'), $reserved_table_name))
      {
        $validator->errors()->add('table_name', $request->input('table_name').' is reserved');
      }
    });

    if ($validator->fails()) {
      $obj->error = 'validator';
      $obj->validator = $validator;
      return $obj;
    }

    $table = new Tables;
    $table->name = $request->get('name');
    $table->table_name = $request->get('table_name');
    $table->skin = $request->get('skin');
    $table->skin_admin = $request->skin_admin;
    $table->extends = $request->get('extends');
    $table->section = $request->get('section');
    $table->lists = $request->input('lists', 10);
    $table->editor = $request->input('editor', 0);
    $table->auth_list = $request->get('auth_list');
    $table->auth_write = $request->get('auth_write');
    $table->auth_read = $request->get('auth_read');
    $table->enable_reply = $request->input('enable_reply', 0);
    $table->enable_comment = $request->input('enable_comment', 0);
    $table->enable_qna = $request->input('enable_qna', 0);
    $table->enable_password = $request->input('enable_password', 0);
    $table->save();

    //set roles
    $table->roles_list()->detach();
    $this->add_roles($table, 'list', $request->get('roles-list'));
    $this->add_roles($table, 'read', $request->get('roles-read'));
    $this->add_roles($table, 'write', $request->get('roles-write'));

    return $obj;
  }


  /*
    * Excute BBS Update
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function _update($request, $table)
  {
    $obj = new \stdClass;
    $obj->error = false;

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'skin' => 'required',
    ]);

    $validator->sometimes('table_name', 'unique:bbs_tables', function ($input) use ($table) {
      return strtolower($input->table_name) != strtolower($table->table_name);
    });

    if ($validator->fails()) {
      $obj->error = 'validator';
      $obj->validator = $validator;
      return $obj;
    }

    $table->name = $request->name;
    $table->table_name = $request->table_name;
    $table->skin = $request->skin;
    $table->skin_admin = $request->skin_admin;
    $table->extends = $request->extends;
    $table->section = $request->get('section');
    $table->lists = $request->input('lists', 10);
    $table->editor = $request->input('editor', 0);
    $table->auth_list= $request->get('auth_list');
    $table->auth_write = $request->get('auth_write');
    $table->auth_read = $request->get('auth_read');
    $table->enable_reply = $request->input('enable_reply', 0);
    $table->enable_comment = $request->input('enable_comment', 0);
    $table->enable_qna = $request->input('enable_qna', 0);
    $table->enable_password = $request->input('enable_password', 0);

    $table->save();

    //set roles
    $table->roles_list()->detach();
    $this->add_roles($table, 'list', $request->get('roles-list'));
    $this->add_roles($table, 'read', $request->get('roles-read'));
    $this->add_roles($table, 'write', $request->get('roles-write'));

    return $obj;
  }

  public function _configUpdate($request) {
    BbsConfig::where('k', 'extends')->update(['v'=>$request->extends]);
    BbsConfig::where('k', 'section')->update(['v'=>$request->section]);
  }


  private function add_roles($table, $type, $roles) {
    switch($type) {
      case 'list':
        if ($this->has_roles($roles)) {
          $table->roles_list()->attach($roles);
        }
        break;
      case 'read':
        if ($this->has_roles($roles)) {
          $table->roles_read()->attach($roles);
        }
        break;
      case 'write':
        if ($this->has_roles($roles)) {
          $table->roles_write()->attach($roles);
        }
        break;
    }
  }
  /**
   * role has a value or not
   * @return Boolean
   */
  private function has_roles($roles){
    if(!is_array($roles))
      return false;
    else{
      foreach($roles as $v){
        if($v == 0)
          return false;
      }
    }
    return true;
  }

    /*
     * Show BBS Board
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function _show($request, $table)
  {
    $skin_dir =  resource_path('views/bbs/templates/');
    $tmp_skins = array_map('basename',\File::directories($skin_dir));

    //키를 text로 변경
    foreach($tmp_skins as $v){
      $skins[$v] = $v;
    }

    return ['cfg'=> $table, 'skins' => $skins, 'roles' => Role::get()];
  }

  /*
    * BBS Edit Form
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function _edit($id)
  {
  }

  /*
    * Delete BBS
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function _destroy($id)
  {
    $cfg = Tables::findOrFail($id);
    $cfg->delete();
  }

  private function admin_extends() {
    $config = BbsConfig::get();
    $cfg = new \stdclass;
    foreach($config as $v) {
      $cfg->{$v->k} = $v->v;
    }

    return $cfg;
  }
}
