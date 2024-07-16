<?php
namespace Wangta69\Bbs;


use Wangta69\Bbs\Models\BbsTables as Tables;
use Wangta69\Bbs\Models\BbsArticles as Articles;
use Wangta69\Bbs\Models\BbsFiles as Files;
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
   * use Wangta69\Bbs\BbsService;
   * $prev = BbsService::next(2, $article->id);
   * $next = BbsService::previous(2, $article->id);
   */
  public static function next($bbs_table_id,  $item_id){
    return Articles::where('bbs_table_id', $bbs_table_id)->where('id', '>', $item_id)->orderBy('id','asc')->first();    
  }
  public static  function previous($bbs_table_id, $item_id){
    return Articles::where('bbs_table_id', $bbs_table_id)->where('id', '<', $item_id)->orderBy('id','desc')->first();
  }
}

class ParamData{
  public $enc='';
  public $dec='';
  public $default='';
}
