<?php

namespace Wangta69\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


use Wangta69\Bbs\Models\Role;

class BbsTables extends Model
{
    use Sortable;

    public $sortable = [];

    protected $fillable = ['name', 'table_name', 'skin'];//

    protected $table        = 'bbs_tables';
    //protected $dateFormat = 'U';
    protected $primaryKey = 'id';


    public function get_config_by_tablename($tbl_name){
       // return $tbl_name;
        return $this->where('table_name', $tbl_name)->first();
    }

    public function bbs_articles()
    {
       // return $this->hasMany('App\Models\Plays', 'user_id');
    }

    /**
     * roles_read : 읽기 권한
     */
    public function roles_read()
    {
        //select `roles`.*, `bbs_roles`.`bbs_tables_id` as `pivot_bbs_tables_id`, `bbs_roles`.`read_role_id` as `pivot_read_role_id` from `roles` inner join `bbs_roles` on `roles`.`id` = `bbs_roles`.`read_role_id` where `bbs_roles`.`bbs_tables_id` = 2
        return $this->belongsToMany(Role::class, 'bbs_roles', 'bbs_tables_id', 'read_role_id');
    }

    public function roles_write()
    {
        //select `roles`.*, `bbs_roles`.`bbs_tables_id` as `pivot_bbs_tables_id`, `bbs_roles`.`read_role_id` as `pivot_read_role_id` from `roles` inner join `bbs_roles` on `roles`.`id` = `bbs_roles`.`read_role_id` where `bbs_roles`.`bbs_tables_id` = 2
        return $this->belongsToMany(Role::class, 'bbs_roles', 'bbs_tables_id', 'write_role_id');
    }


    private function get_roles(){

            $roles = $this->hasMany('Wangta69\Bbs\Models\BbsRoles', 'bbs_tables_id');
            $rtn = [];

            foreach($roles->get() as $k => $v){
                if($v->read_role_id) $rtn["read"][] = $v->read_role_id;
                if($v->write_role_id) $rtn["write"][] = $v->write_role_id;
            }

            return $rtn;
    }

    public function roles_count($flag){
        $roles = $this->get_roles();

        if(isset($roles[$flag]))
            return count($roles[$flag]);
        else
            return 0;
    }


    /*
    public function hasWriteRole($role)
    {
        if (is_string($role)) {
            return $this->roles_write->contains('name', $role);
        }
        return !! $role->intersect($this->roles_write)->count();
    }
      public function hasReadRole($role)
    {
        if (is_string($role)) {
            return $this->roles_read->contains('name', $role);
        }
        return !! $role->intersect($this->roles_read)->count();
    }
*/
    /**
     * 설정된 role정보를 가져온다.
     * @param String $flag read/write
     * @return Boolean;
     */
    public function hasPermission($mode)
    {
        switch($mode){
            case "read":
                if($this->auth_read == 'none')
                    return true;
                else{

                }
            break;
            case "write":
                switch($this->auth_write){
                    case "none":
                        return true;
                    break;
                    case "login":
                        if(\Auth::check())
                            return true;
                        else
                            return false;
                    break;
                    case "role":
                        if(!\Auth::check())
                            return false;
                        else
                            return $this->hasRole($mode);
                        break;
                }

            break;
        }
    }

    private function hasRole($mode){
        //먼저 현재 롤을 가져온다.
        $roles = $this->get_roles();
        if(isset($roles[$mode])){//role 이 세팅되어 이으면 체크한다.

            if(!\Auth::user())
                return false;


            //$user_roles = \Auth::user()->roles->toArray();
            $user_roles = \Auth::user()->roles;

            foreach($user_roles as $v){
                if(in_array($v->id, $roles[$mode]))
                    return true;
            }


            return false;
        }
        else
            return true;
    }


}
