<?php

namespace Pondol\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


use Pondol\Bbs\Models\Role;

class Bbs_tables extends Model
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
}
