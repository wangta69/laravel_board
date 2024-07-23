<?php

namespace Pondol\Bbs\Models;

use Illuminate\Database\Eloquent\Model;

class BbsCategories extends Model
{
    public $sortable = [];

    protected $fillable = [];

    protected $table = 'bbs_categories';
    //protected $dateFormat = 'U';
    protected $primaryKey = 'id';
}
