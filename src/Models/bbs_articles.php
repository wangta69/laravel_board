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
        return $this->belongsTo('App\User');
    }
    
    public function bbs_article()
    {
        return $this->belongsTo('Pondol\Bbs\Models\Bbs_tables');
        //return $this->belongsTo('Pondol\Bbs\Models\Bbs_tables', 'user_id');
    }
    
    /*
     * 연결 파일 relation
     * 
     * @return ArticleFiles 모델
     */
    public function files() {
       // $foreignKey = $this->getForeignKey();
       // $localKey = $this->getKeyName();
        //$instance = new \Visualplus\Board\ArticleFiles;
        //$instance->setTable($this->table.'_files');
        
       // return new HasMany($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
       
       return $this->HasMany('Pondol\Bbs\Models\Bbs_files');
    }
    /*
     * 게시글의 소유자인지 확인
     * 
     * @param \App\User $user
     * @return bool
     */
    public function isOwner($user) {
        return ($user != null && $this->user_id == $user->id);
    }
    
}
