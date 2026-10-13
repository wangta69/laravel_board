<?php

/**
 * 독립페이지 아이템용 댓글 컴포넌트
 */

namespace Pondol\Bbs\View\Components;

use Illuminate\View\Component;
use Pondol\Bbs\Models\BbsItemComment;

class ItemCommnents extends Component
{
    public $item;

    public $item_id;

    public $skin;

    public function __construct($item = null, $itemId = null, $skin = 'default')
    {
        $this->item = $item;
        $this->item_id = $itemId;
        $this->skin = $skin ?: 'default'; // skin 미지정 시 'default' 자동 적용
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $comments = BbsItemComment::select('id', 'item', 'writer', 'content', 'reply_depth', 'created_at')
            ->where('item', $this->item)
            ->where('item_id', $this->item_id)
            ->orderBy('order_num', 'desc')
            ->get();

        // [수정] bbs. -> bbs:: 로 네임스페이스 적용
        return view('bbs::components.comments.user.'.$this->skin.'.comments', [
            'item' => $this->item,
            'item_id' => $this->item_id,
            'comments' => $comments,
        ]);
    }
}
