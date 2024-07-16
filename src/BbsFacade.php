<?php
namespace Wangta69\Bbs;

use Illuminate\Support\Facades\Facade;

class BbsFacade extends Facade
{
  protected static function getFacadeAccessor() {
    return 'bbs';
  }
}
