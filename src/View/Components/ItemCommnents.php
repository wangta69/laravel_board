<?php
namespace Pondol\Bbs\View\Components;

use Illuminate\View\Component;
use Pondol\Bbs\Models\BbsItemComment;

class ItemCommnents extends Component
{
  public $item;
  public $item_id;
  public $skin;
  public function __construct($item=null, $itemId=null, $skin=null) {

    $this->item = $item;
    $this->item_id = $itemId;
    $this->skin = $skin;
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

    return view('bbs::components.comments.'.$this->skin.'.comments', [
      'item' => $this->item,
      'item_id' => $this->item_id,
      'comments' => $comments
    ]);
  }
}
