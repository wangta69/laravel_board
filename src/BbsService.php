<?php
namespace Pondol\Bbs;


use Pondol\Bbs\Models\Bbs_tables as Tables;
use Pondol\Bbs\Models\Bbs_articles as Articles;
use Pondol\Bbs\Models\Bbs_files as Files;

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
        $bbs_table_id   = $params['id'];
        $cnt    = isset($params["cnt"]) ? $params["cnt"]: 5;

        
        $list = Articles::where('bbs_table_id', $bbs_table_id)->limit($cnt)->orderBy('created_at', 'desc');//->paginate($this->itemsPerPage);
        return $list->get();
    }
    
    public function get_table_info_by_table_name($tbl_name){
        $tables = new Tables;
        return $tables->where('table_name', $tbl_name)->first();
    }
    
    public function get_table_info_by_id($id){
        return Tables::findOrFail($id);
    }
    
    /*
    public static function enc_params($arr){
        return encrypt($arr);
    }
    
    public static function dec_params($default_arr, $data=null){
        return $data==null ? $default_arr:decrypt($data);
    }
    */
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

}

class ParamData{
    
    public $enc='';
    public $dec='';
    public $default='';
}
