<?php

namespace Pondol\Bbs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class BbsItemComment extends Model
{
    use SoftDeletes, Sortable;

    protected $table = 'bbs_item_comments';

    protected $primaryKey = 'id';

    public $sortable = [];

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'integer',
        'order_num' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        $authModel = config('auth.providers.users.model', \App\Models\User::class);

        return $this->belongsTo($authModel);
    }

    public function article()
    {
        return $this->belongsTo(BbsArticles::class, 'bbs_articles_id');
    }

    /**
     * 댓글 소유자 확인
     *
     * @param  mixed  $user
     * @return bool
     */
    public function isOwner($user)
    {
        return $user !== null && (int) $this->user_id === (int) $user->id;
    }
}
