<?php

// $bbs_allowed_image = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
/**
 * 첨부된 이미지를 thub 으로 만들어서 표시
 */
function bbs_get_thumb($file, $width = null, $height = null)
{
    return Pondol\Bbs\BbsService::get_thumb($file, $width, $height);
}

/**
 * 첨부된 이미지를 화면에 표시
 */
function bbs_image_url_from_storage($path)
{
    if (! $path) {
        return null;
    }

    $ext = substr($path, strrpos($path, '.') + 1);
    $bbs_allowed_image = config('pondol-bbs.allowed_images');

    if (in_array(strtolower($ext), $bbs_allowed_image)) {

        // [Case 1] 예전 데이터 처리: 'public/'으로 시작하는 경우
        // 결과: /storage/bbs/...
        if (strpos($path, 'public/') === 0) {
            return str_replace('public', '/storage', $path);
        }

        // [Case 2] 새로운 데이터 처리: 'bbs/'로 시작하는 경우
        // 'public/'이라는 글자가 없으므로 그냥 앞에 '/storage/'를 붙여줍니다.
        // 결과: /storage/bbs/...
        return '/storage/'.$path;

    } else {
        return null;
    }
}

/**
 * @params Array $params : ['name'=>'free', 'cnt'=>4]
 */
function bbs_get_latest($params)
{
    // return App\MyClass::myMethod();
    return Pondol\Bbs\BbsService::get_latest($params);
}

function resizeImage($file, $width = 0, $height = 0)
{

    // echo 'width:'.$width.', height:'.$height.PHP_EOL;
    if ($file) {
        if ($width == null && $height == null) {
            return str_replace(['public'], ['/storage'], $file);
        }

        $name = substr($file, strrpos($file, '/') + 1);
        $thum_dir = substr($file, 0, -strlen($name)).$width.'_'.$height;
        // return $name;
        $thum_to_storage = storage_path().'/app/'.$thum_dir;

        if (! file_exists($thum_to_storage.'/'.$name)) {// thumbnail 이미지를 돌려준다.
            $file_to_storage = storage_path().'/app/'.$file;
            $image = new Pondol\Image\GetHttpImage;

            try {
                // $image->read($file_to_storage)->set_size($width, $height)->copyimage()->save($thum_to_storage);

                $result = $image->read($file_to_storage)->resize($width, $height)->copyimage2();
                if ($result) {
                    $result->save($thum_to_storage);
                }
            } catch (\Exception $e) {
            }
        }

        return str_replace(['public'], ['/storage'], $thum_dir).'/'.$name;
    } else {
        return '';
    }
}
