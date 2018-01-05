<?php

namespace Pondol\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Bbs_files extends Model
{
    use Sortable;

    public $sortable = [];

    protected $fillable = [];

    protected $table        = 'bbs_files';
    //protected $dateFormat = 'U';
    protected $primaryKey = 'id';
    

}