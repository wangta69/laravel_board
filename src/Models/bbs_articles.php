<?php

namespace Pondol\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bbs_articles extends Model
{
    use Sortable,
    SoftDeletes;

    public $sortable = [];

    protected $fillable = [];

    protected $table        = 'bbs_articles';
    //protected $dateFormat = 'U';
    protected $primaryKey = 'id';
    
   /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    /*
     * 
     * @return App\User
     * If you have Defferent UserTable, OverWrite This.
     */
    public function user() {
       return $this->belongsTo(config('bbs.user'));
    }
    
    public function table()
    {
        return $this->belongsTo('Pondol\Bbs\Models\Bbs_tables', 'bbs_table_id');
    }
    
    /*
     * 
     * @return ArticleFiles Model
     */
    public function comments() {
       return $this->HasMany('Pondol\Bbs\Models\Bbs_comments');
    }
    
    
    /*
     * 
     * @return ArticleFiles Model
     */
    public function files() {
       return $this->HasMany('Pondol\Bbs\Models\Bbs_files');
    }
    /*
     * find owner of a article
     * 
     * @param \App\User $user
     * @return bool
     */
    public function isOwner($user) {
        return ($user != null && $this->user_id == $user->id);
    }
    
}
