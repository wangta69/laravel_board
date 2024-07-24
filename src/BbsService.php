<?php
namespace Pondol\Bbs;


use Pondol\Bbs\Models\BbsTables as Tables;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsFiles as Files;
use Pondol\Image\GetHttpImage;
use Auth;
class BbsService
{

  /**
  * @param String $flag : write, read
  */
  public function hasPermission($flag, $tbl_id) {
      ## first check bbs_role
  }

  /**
   * 내용가져오기
   * @param Array $params = array(id, cnt)
   */
  public static function get_latest($params){
    $bbs_table_id = $params['id'];
    $cnt = isset($params["cnt"]) ? $params["cnt"]: 5;

    $list = Articles::where('bbs_table_id', $bbs_table_id)->limit($cnt)->orderBy('created_at', 'desc');//->paginate($this->itemsPerPage);
    return $list->get();
  }

  public function get_table_info_by_table_name($tbl_name){
    $tables = new Tables;
    // return $tables->where('table_name', $tbl_name)->first();
    return $tables->get_config_by_tablename($tbl_name);
  }

  public function get_table_info_by_id($id){
    return Tables::findOrFail($id);
  }

  /**
   * 전송할 데이타와 전송된 데이타를 동시에 처리하여 전달
   */
  public static function create_params($default_arr, $data=null){
    $paramData = new ParamData();
    if($data == null){
      $paramData->default = $default_arr;
      $paramData->enc = encrypt($default_arr);
      $paramData->dec = $default_arr;
    }else{
      $paramData->default = $default_arr;
      $paramData->enc = $data;
      $paramData->dec = decrypt($data);
    }
    return $paramData;
    // $rtn['']
  //  return $data==null ? $default_arr:decrypt($data);
  }

  public static function hasRole($role)
  {
    if (Auth::user()->roles->isEmpty()) return false;
    return (Auth::user()->roles->contains('id', null, $role) || Auth::user()->roles->contains('name', null, $role));
  }


  /**
   * Checks if the user has a roles.
   *
   * @param $roles String|Array  in case of multi user seperated by ','
   * @param bool $all
   * @return bool|int
   */
  public static function hasRoles($roles, $all = false)
  {
    $roles = !is_array($roles) ? explode(",", $roles) : $roles;

    $hasRoles = 0;
    if (!Auth::user()) {
      return 0;
    } else {
      foreach ($roles as $role) {
        if (Auth::user()->roles->contains('id', null, $role) || Auth::user()->roles->contains('name', null, $role)) $hasRoles++;
      }
      return ($all) ? ($hasRoles == count($roles)) : ($hasRoles);
    }
  }

  /**
   * 이전 다음 찾기
   * use Pondol\Bbs\BbsService;
   * $prev = BbsService::next(2, $article->id);
   * $next = BbsService::previous(2, $article->id);
   */
  public static function next($bbs_table_id,  $item_id){
    return Articles::where('bbs_table_id', $bbs_table_id)->where('id', '>', $item_id)->orderBy('id','asc')->first();    
  }
  public static  function previous($bbs_table_id, $item_id){
    return Articles::where('bbs_table_id', $bbs_table_id)->where('id', '<', $item_id)->orderBy('id','desc')->first();
  }

    /**
   * @param String $file  public/bbs/5/201804/37/filename.jpeg
   */
  public static function get_thumb($file, $width=null, $height=null) {
    if ($file) {
      if($width == null &&  $height == null) {
        return str_replace(["public"], ["/storage"], $file);
      } else if($width == null ) {
        $width = $height;
      } else if($height == null) {
        $height = $width;
      }
      $name = substr($file, strrpos($file, '/') + 1);
      $thum_dir = substr($file, 0, -strlen($name)).$width."_".$height;
      $thum_to_storage = storage_path() .'/app/'.$thum_dir;

      if(!file_exists($thum_to_storage."/".$name)){//thumbnail 이미지를 돌려준다.
        $file_to_storage = storage_path() .'/app/'.$file;
        $image = new GetHttpImage();

        try {
          // $image->read($file_to_storage)->set_size($width, $height)->copyimage()->save($thum_to_storage);
          $result = $image->read($file_to_storage)->set_size($width, $height)->copyimage();
          if ($result) {
              $result->save($thum_to_storage);
          }
        } catch (\Exception $e) {
        }
      }

      return str_replace(["public"], ["/storage"], $thum_dir)."/".$name;
    }else
      return '';
  }
}

class ParamData{
  public $enc='';
  public $dec='';
  public $default='';
}
