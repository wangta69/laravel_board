<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use \Pondol\Bbs\PluginsSmartEditorService as SmartEditor;

class PluginsController extends \Pondol\Bbs\PluginsController
{

    protected $uploadPath = "../storage/app/editor/";
    
    public function index($plugins, $action=null){
        

        switch($plugins){
            case "smart-editor":
                $smartEditor =  new SmartEditor;
                switch($action){
                    case "photo-uploader"://photo attach window open
                        return $smartEditor->getPhotoUploader();
                    break;
                    case "callback"://after photouploaded call callback
                        return $smartEditor->getCallback();
                        break;
                    //case "file-upload"://execute file upload
                    //    return $smartEditor->postFileUpload();
                    //    break;
                    default://editor window open
                        return $smartEditor->getSmartEditorSkin();
                    break;
                }
                
                break;
        }
    }
    
    public function fileUpload(Request $request, $plugins){
        $smartEditor =  new SmartEditor;
        $url = $smartEditor->postFileUpload($request);
        
        return redirect($url);
    }
}
