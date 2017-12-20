<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;

class PluginsController extends \Pondol\Bbs\PluginsController
{

    protected $uploadPath = "../storage/app/editor/";
    
    public function index($plugins){
        switch($plugins){
            case "smart-editor-skin":
                return $this->getSmartEditorSkin();
                break;
        }
    }
}
