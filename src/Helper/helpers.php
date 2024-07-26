<?php

// $bbs_allowed_image = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
/**
 * 첨부된 이미지를 thub 으로 만들어서 표시
 */
function bbs_get_thumb($file, $width=null, $height=null){
  return Pondol\Bbs\BbsService::get_thumb($file, $width, $height);
}

/**
 * 첨부된 이미지를 화면에 표시
 */
function bbs_image_url_from_storage($path){ 
  $ext = substr($path, strrpos($path, '.') + 1);
  $bbs_allowed_image = config('bbs.allowed_images');
  if(in_array(strtolower($ext), $bbs_allowed_image)) {
    return str_replace(["public"], ["/storage"], $path);
  } else {
    return null;
  }
}

/**
 * @params Array $params : ['name'=>'free', 'cnt'=>4]
 */
function bbs_get_latest($params){
  // return App\MyClass::myMethod();
  return Pondol\Bbs\BbsService::get_latest($params);
}