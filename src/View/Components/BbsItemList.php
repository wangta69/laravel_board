<?php
/**
 * 생성된 bbs 리스트를 자동으로 출력
 */
namespace Pondol\Bbs\View\Components;

use Illuminate\View\Component;
use Pondol\Bbs\Models\BbsTables;
class BbsItemList extends Component
{
  public function __construct() {
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    $items = BbsTables::orderBy('created_at', 'desc')->get();
    return view('bbs::components.navigation-items', compact('items'));
  }
}
