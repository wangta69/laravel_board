<?php

namespace Wangta69\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class BbsArticles extends Model
{
  use Sortable,
  SoftDeletes,
  Notifiable;

  public $sortable = [];

  protected $fillable = [];

  protected $table = 'bbs_articles';
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

  public function table()
  {
    return $this->belongsTo('Wangta69\Bbs\Models\BbsTables', 'bbs_table_id');
  }

  /*
    *
    * @return BbsComments Model
    */
  public function comments() {
    return $this->HasMany('Wangta69\Bbs\Models\BbsComments');
  }

  public function comment() {
    return $this->comments()->latest();
  }
  /*
    *
    * @return BbsFiles Model
    */
  public function files() {
    return $this->HasMany('Wangta69\Bbs\Models\BbsFiles');
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
