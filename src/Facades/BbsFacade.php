<?php
namespace Pondol\Bbs\Facades;
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