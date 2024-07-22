<?php

namespace Wangta69\Bbs\Models;

use Illuminate\Database\Eloquent\Model;

class BbsConfig extends Model
{
    public $sortable = [];

    protected $fillable = [];

    protected $table = 'bbs_config';
    //protected $dateFormat = 'U';
    protected $primaryKey = 'id';
}
