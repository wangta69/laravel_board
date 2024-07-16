<?php

namespace App\Http\Controllers\Bbs;

use Illuminate\Http\Request;
use \Wangta69\Bbs\PluginsSmartEditorService as SmartEditor;

class PluginsController
{

  protected $uploadPath = "../storage/app/editor/";

  public function index($plugins, $action=null){

    switch($plugins){
      case "smart-editor":
        $smartEditor =  new SmartEditor;
        switch($action){
          case "photo-uploader"://photo attach window open
            return $smartEditor->getPhotoUploader();

          case "callback"://after photouploaded call callback
            return $smartEditor->getCallback();

          //case "file-upload"://execute file upload
          //    return $smartEditor->postFileUpload();
          //    break;
          default://editor window open
            return $smartEditor->getSmartEditorSkin();

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
