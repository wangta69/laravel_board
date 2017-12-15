<?php

namespace Pondol\Board\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Wizboard_config extends Model
{
    use Sortable;

    public $sortable = [];

    protected $fillable = [];

    protected $table        = 'wizboard_configs';
    //protected $dateFormat = 'U';
    protected $primaryKey = 'id';
}
