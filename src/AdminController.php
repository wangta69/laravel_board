<?php
namespace Pondol\Bbs;

use Illuminate\Http\Request;

use Route;
use View;

use Pondol\Bbs\Models\Bbs_tables as Tables;
use Pondol\Bbs\Models\Role;

//use Pondol\Bbs\Models\Bbs_roles as Roles;
    

class AdminController extends \App\Http\Controllers\Controller {

	
	public function __construct() {
	    // var_dump(Auth::user());
	}
	
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
    public function createForm(Request $request)
    {
        $skin_dir =  resource_path('views/bbs/templates/');
        $tmp_skins = array_map('basename',\File::directories($skin_dir));
        //키를 text로 변경
        foreach($tmp_skins as $v){
            $skins[$v] = $v;
        }
        


        //return view('bbs.admin.create')->with(compact('skins'));
        return view('bbs.admin.create', ['skins' => $skins, 'blade_extends' => $this->blade_extends, 'roles' => Role::get()]);
    }

	/*
	 * Create BBS
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
    public function store(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'table_name' => 'required',
    		'skin' => 'required',
    	]);

        $table = new Tables;
        $table->name 		= $request->get('name');
        $table->table_name 	= $request->get('table_name');
        $table->skin		= $request->get('skin');
        $table->save();
		
        
        //set roles
        $table->roles_read()->detach();
        
        if ($this->has_roles($request->get('roles-read'))) {
            $table->roles_read()->attach($request->get('roles-read'));
        }

        if ($this->has_roles($request->get('roles-write'))) {
            $table->roles_write()->attach($request->get('roles-write'));
        }
        
        return redirect()->route('bbs.admin');
	}


    /*
     * Excute BBS Update
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $table = Tables::findOrFail($id);
        $table->name        = $request->name;
        $table->table_name  = $request->table_name;
        $table->skin        = $request->skin;

        $table->save();
        
        
     
        //set roles
        $table->roles_read()->detach();
      
        if ($this->has_roles($request->get('roles-read'))) {
            $table->roles_read()->attach($request->get('roles-read'));
        }

        if ($this->has_roles($request->get('roles-write'))) {
            $table->roles_write()->attach($request->get('roles-write'));
        }

         return redirect()->route('bbs.admin');
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
    public function show(Request $request, $id)
    {
        $cfg = Tables::findOrFail($id);
        
        $skin_dir =  resource_path('views/bbs/templates/');
        $tmp_skins = array_map('basename',\File::directories($skin_dir));
        
        //키를 text로 변경
        foreach($tmp_skins as $v){
            $skins[$v] = $v;
        }
        
        return view('bbs.admin.create', ['cfg'=> $cfg, 'skins' => $skins, 'roles' => Role::get(), 'blade_extends' => $this->blade_extends]);
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
        $cfg->delete();
        return redirect()->route('bbs.admin');
    }
    

}
