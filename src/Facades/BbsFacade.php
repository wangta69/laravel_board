<?php
namespace Wangta69\Bbs\Facades;
use Illuminate\Support\Facades\Facade;

class BbsFacade extends Facade
{
    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'bbs'; }
}