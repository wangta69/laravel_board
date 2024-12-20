<?php
/**
 * 생성된 bbs 리스트를 자동으로 출력
 */
namespace Pondol\Bbs\View\Components;

use Illuminate\View\Component;
use Pondol\Bbs\Models\BbsTables;
class BbsCard extends Component
{

  private $table;
  private $cnt;

  public function __construct($table, $cnt=5) {
    $this->table = $table;
    $this->cnt = $cnt;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {

    $items = bbs_get_latest(array('table'=>$this->table->table_name, 'cnt'=>$this->cnt));
    return view('bbs::components.partials.card', ['items'=>$items, 'table'=>$this->table]);
  }
}
