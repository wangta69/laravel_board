<?php
namespace Wangta69\Bbs;

use Illuminate\Http\Request;

use Route;
use View;
use Validator;

use Wangta69\Bbs\Models\BbsTables as Tables;
use Wangta69\Bbs\Models\Role;
use Wangta69\Bbs\BbsService;



class AdminController extends \App\Http\Controllers\Controller {

    protected $bbsSvc;
    protected $cfg;
    public function __construct() {}

    /*
     * BBS Tables List
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

        $list = Tables::orderBy('created_at', 'desc')->paginate($this->itemsPerPage);
        //return view('bbs.admin.index')->with(compact('list', $this->blade_extends));
        return view('bbs.admin.index', ['list' => $list, 'urlParams'=>$urlParams]);
    }

    /*
     * BBS CREATE Form
     *
     * @return \Illuminate\Http\Response
     */
    public function createForm(Request $request, Tables $table)
    {
        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

        $skin_dir =  resource_path('views/bbs/templates/');
        $tmp_skins = array_map('basename',\File::directories($skin_dir));
        //키를 text로 변경
        foreach($tmp_skins as $v){
            $skins[$v] = $v;
        }

        $editors = ['none'=>'None', 'smartEditor'=>'Smart Editor'];


        //return view('bbs.admin.create')->with(compact('skins'));
        return view('bbs.admin.create', ['cfg'=>$table, 'skins' => $skins, 'editors' => $editors, 'roles' => Role::get(), 'urlParams'=>$urlParams]);
    }

    /*
     * Create BBS
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $reserved_table_name = ['admin', 'root'];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'table_name' => 'required|unique:bbs_tables',
            'skin' => 'required',
            //'username' => 'required|unique:users|min:2|max:8',
        ]);

        $validator->after(function($validator) use ($request, $reserved_table_name)
        {
            if (in_array($request->input('table_name'), $reserved_table_name))
            {
                $validator->errors()->add('table_name', $request->input('table_name').' is reserved');
            }
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());


        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

        $table = new Tables;
        $table->name = $request->get('name');
        $table->table_name = $request->get('table_name');
        $table->skin = $request->get('skin');
        $table->lists = $request->input('lists', 10);
        $table->editor = $request->get('editor');
        $table->auth_write = $request->get('auth_write');
        $table->auth_read = $request->get('auth_read');
        $table->enable_reply = $request->input('enable_reply', 0);
        $table->enable_comment = $request->input('enable_comment', 0);
        $table->enable_qna = $request->input('enable_qna', 0);
        $table->save();


        //set roles
        $table->roles_read()->detach();

        if ($this->has_roles($request->get('roles-read'))) {
            $table->roles_read()->attach($request->get('roles-read'));
        }

        if ($this->has_roles($request->get('roles-write'))) {
            $table->roles_write()->attach($request->get('roles-write'));
        }

        return redirect()->route('bbs.admin.index', ['urlParams'=>$urlParams->enc]);
    }


    /*
     * Excute BBS Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tables $table)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'skin' => 'required',
            //'username' => 'required|unique:users|min:2|max:8',
        ]);

        $validator->sometimes('table_name', 'unique:bbs_tables', function ($input) use ($table) {
            return strtolower($input->table_name) != strtolower($table->table_name);
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $table->name = $request->name;
        $table->table_name = $request->table_name;
        $table->skin = $request->skin;
        $table->lists = $request->input('lists', 10);
        $table->editor = $request->get('editor');
        $table->auth_write = $request->get('auth_write');
        $table->auth_read = $request->get('auth_read');
        $table->enable_reply = $request->input('enable_reply', 0);
        $table->enable_comment = $request->input('enable_comment', 0);
        $table->enable_qna = $request->input('enable_qna', 0);

       // print_r($request->all());
       // echo $request->input('enable_comment', 0);
       // exit;
        $table->save();


        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

        //set roles
        $table->roles_read()->detach();

        if ($this->has_roles($request->get('roles-read'))) {
            $table->roles_read()->attach($request->get('roles-read'));
        }

        if ($this->has_roles($request->get('roles-write'))) {
            $table->roles_write()->attach($request->get('roles-write'));
        }

         return redirect()->route('bbs.admin.index', ['urlParams'=>$urlParams->enc]);
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
    public function show(Request $request, Tables $table)
    {

        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));

        $skin_dir =  resource_path('views/bbs/templates/');
        $tmp_skins = array_map('basename',\File::directories($skin_dir));

        //키를 text로 변경
        foreach($tmp_skins as $v){
            $skins[$v] = $v;
        }

        return view('bbs.admin.create', ['cfg'=> $table, 'skins' => $skins, 'roles' => Role::get(), 'urlParams'=>$urlParams]);
        //return view('bbs.admin.create')->with(compact('cfg', 'skins'));
    }

    /*
     * BBS Edit Form
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /*
     * Delete BBS
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $cfg = Tables::findOrFail($id);
        $urlParams = BbsService::create_params($this->deaultUrlParams, $request->input('urlParams'));
        $cfg->delete();

        if($request->ajax()){
            return Response::json(['result'=>true, "code"=>"000"], 200);
        }else{
            return redirect()->route('bbs.admin.index', ['urlParams'=>$urlParams->enc]);
        }
    }
}
