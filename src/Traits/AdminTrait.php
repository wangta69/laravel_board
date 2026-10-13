<?php

namespace Pondol\Bbs\Traits;

use Illuminate\Http\Request;
use Pondol\Auth\Models\Role\Role;
use Pondol\Bbs\Models\BbsTables as Tables;
use Validator;

trait AdminTrait
{
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

        return ['list' => $list];
    }

    /*
     * BBS CREATE | EDIT Form
     *
     * @return \Illuminate\Http\Response
     */
    public function _createForm($request, $table)
    {
        // [수정] 패키지 내장 스킨 + 사용자 커스텀 스킨 자동 스캔
        $skins = $this->getSkins('user');
        $skins_admin = $this->getSkins('admin');

        // set default value;
        $table->lists = $table->lists ?? 10;
        $table->component = $table->component ?? 'bbs::default';
        $table->extends = $table->extends ?? 'bbs::front-app';
        $table->section = $table->section ?? 'content';

        return [
            'table' => $table,
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
            'extends' => 'required',
            // 'username' => 'required|unique:users|min:2|max:8',
        ]);

        $validator->after(function ($validator) use ($request, $reserved_table_name) {
            if (in_array($request->input('table_name'), $reserved_table_name)) {
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
        $table->blade = $request->get('blade');
        $table->component = $request->get('component');
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

        // set roles
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
        $table->blade = $request->get('blade');
        $table->component = $request->get('component');
        $table->extends = $request->extends;
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

        // set roles
        $table->roles_list()->detach();
        $this->add_roles($table, 'list', $request->get('roles-list'));
        $this->add_roles($table, 'read', $request->get('roles-read'));
        $this->add_roles($table, 'write', $request->get('roles-write'));

        return $obj;
    }

    private function add_roles($table, $type, $roles)
    {
        switch ($type) {
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
     *
     * @return bool
     */
    private function has_roles($roles)
    {
        if (! is_array($roles)) {
            return false;
        } else {
            foreach ($roles as $v) {
                if ($v == 0) {
                    return false;
                }
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
        $obj = new \stdClass;
        $obj->roles = Role::get();
        $obj->skins = $this->getSkins('user');

        return $obj;
    }

    /*
      * BBS Edit Form
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
    public function _edit($id) {}

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

    /**
     * 스킨 디렉터리 자동 스캔 (내장 기본 스킨 + 사용자 커스텀 스킨 통합)
     *
     * @param  string  $type  'user' 또는 'admin'
     * @return array
     */
    private function getSkins($type = 'user')
    {
        $skins = [];

        // 1. 패키지 내장 기본 스킨 디렉터리 (기본 5종)
        $packagePath = __DIR__.'/../resources/views/templates/'.$type;
        if (\File::isDirectory($packagePath)) {
            foreach (\File::directories($packagePath) as $dir) {
                $name = basename($dir);
                $skins[$name] = $name;
            }
        }

        // 2. 사용자가 publish했거나 새로 생성한 라라벨 표준 디렉터리
        $vendorPath = resource_path('views/vendor/bbs/templates/'.$type);
        if (\File::isDirectory($vendorPath)) {
            foreach (\File::directories($vendorPath) as $dir) {
                $name = basename($dir);
                $skins[$name] = $name;
            }
        }

        // 3. (하위 호환) 기존 views/bbs 디렉터리
        $legacyPath = resource_path('views/bbs/templates/'.$type);
        if (\File::isDirectory($legacyPath)) {
            foreach (\File::directories($legacyPath) as $dir) {
                $name = basename($dir);
                $skins[$name] = $name;
            }
        }

        return $skins;
    }
}
