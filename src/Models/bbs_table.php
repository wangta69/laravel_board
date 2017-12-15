<?php

namespace Pondol\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Bbs_table extends Model
{
    use Sortable;

    public $sortable = [];

    protected $fillable = [];

    protected $table        = 'bbs_tables';
    //protected $dateFormat = 'U';
    protected $primaryKey = 'id';
    
   /* 
    public function get_config_by_tablename($tbl_name){
        return $this->where('table_name', $tbl_name)->first();
    }

    */
}
