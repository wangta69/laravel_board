<?php

namespace Wangta69\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;


class BbsItemComment extends Model
{
  use Sortable,
  SoftDeletes;

  public $sortable = [];
  protected $fillable = [];
  // protected $table = 'bbs_item_comments';
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
    $auth = config('auth.providers.users.model');
    return $this->belongsTo($auth);
  }

  public function article()
  {
    return $this->belongsTo('Wangta69\Bbs\Models\BbsArticles', 'bbs_articles_id');
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
