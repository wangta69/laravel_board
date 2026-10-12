<?php

namespace Pondol\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

class BbsArticles extends Model
{
    use Notifiable, SoftDeletes, Sortable;

    protected $table = 'bbs_articles';

    protected $primaryKey = 'id';

    public $sortable = [];

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     * (Laravel 10, 11 호환)
     *
     * @var array
     */
    protected $casts = [
        'top' => 'boolean',
        'hit' => 'integer',
        'comment_cnt' => 'integer',
        'order_num' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * User 관계 설정 (폴백 기본값 포함)
     */
    public function user()
    {
        $authModel = config('auth.providers.users.model', \App\Models\User::class);

        return $this->belongsTo($authModel);
    }

    public function table()
    {
        return $this->belongsTo(BbsTables::class, 'bbs_table_id');
    }

    public function category()
    {
        return $this->belongsTo(BbsCategories::class, 'bbs_category_id');
    }

    public function comments()
    {
        return $this->hasMany(BbsComments::class, 'bbs_articles_id');
    }

    public function comment()
    {
        return $this->comments()->latest();
    }

    public function files()
    {
        return $this->hasMany(BbsFiles::class, 'bbs_articles_id');
    }

    /**
     * 게시글 소유자 확인
     *
     * @param  mixed  $user
     * @return bool
     */
    public function isOwner($user)
    {
        return $user !== null && (int) $this->user_id === (int) $user->id;
    }
}
