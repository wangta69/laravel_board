<?php

namespace Pondol\Bbs;

use Auth;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsTables as Tables;

class BbsService
{
    /**
     * @param  string  $flag  : write, read
     */
    public function hasPermission($flag, $tbl_id)
    {
        // # first check bbs_role
    }

    /**
     * 내용가져오기
     *
     * @param  array  $params  = array(id, cnt)
     */
    public static function get_latest($params)
    {
        $table_name = $params['table'];
        $cnt = isset($params['cnt']) ? $params['cnt'] : 5;

        $list = Articles::select('bbs_articles.id', 'bbs_articles.writer', 'bbs_articles.title', 'bbs_articles.created_at')
            ->join('bbs_tables', function ($join) {
                $join->on('bbs_tables.id', '=', 'bbs_articles.bbs_table_id');

            })->where('bbs_tables.table_name', $table_name)->limit($cnt)->orderBy('bbs_articles.created_at', 'desc'); // ->paginate($this->itemsPerPage);

        return $list->get();
    }

    public function get_table_info_by_table_name($tbl_name)
    {
        $tables = new Tables;

        // return $tables->where('table_name', $tbl_name)->first();
        return $tables->get_config_by_tablename($tbl_name);
    }

    public function get_table_info_by_id($id)
    {
        return Tables::findOrFail($id);
    }

    /**
     * 전송할 데이타와 전송된 데이타를 동시에 처리하여 전달
     */
    public static function create_params($default_arr, $data = null)
    {
        $paramData = new ParamData;
        if ($data == null) {
            $paramData->default = $default_arr;
            $paramData->enc = encrypt($default_arr);
            $paramData->dec = $default_arr;
        } else {
            $paramData->default = $default_arr;
            $paramData->enc = $data;
            $paramData->dec = decrypt($data);
        }

        return $paramData;
    }

    public static function hasRole($role)
    {
        if (! Auth::check() || ! Auth::user()->roles || Auth::user()->roles->isEmpty()) {
            return false;
        }

        return Auth::user()->roles->contains('id', $role) || Auth::user()->roles->contains('name', $role);
    }

    /**
     * Checks if the user has a roles.
     *
     * @param  string|array  $roles  in case of multi user separated by ','
     * @param  bool  $all
     * @return bool|int
     */
    public static function hasRoles($roles, $all = false)
    {
        if (! Auth::check() || ! Auth::user()->roles) {
            return 0;
        }

        $roles = ! is_array($roles) ? explode(',', $roles) : $roles;
        $hasRoles = 0;

        foreach ($roles as $role) {
            $role = trim($role);
            if (Auth::user()->roles->contains('id', $role) || Auth::user()->roles->contains('name', $role)) {
                $hasRoles++;
            }
        }

        return ($all) ? ($hasRoles === count($roles)) : $hasRoles;
    }

    /**
     * 목록 순서(order_num) 기준 이전글 / 다음글 조회
     *
     * @param  int|object  $tableIdOrArticle  게시판 ID 또는 BbsArticles 모델
     * @param  int|null  $articleId  게시글 ID
     */
    public static function next($tableIdOrArticle, $articleId = null)
    {
        $article = ($tableIdOrArticle instanceof Articles)
            ? $tableIdOrArticle
            : Articles::find($articleId);

        if (! $article) {
            return null;
        }

        // 목록(order_num ASC)에서 현재 글 다음(아래)에 나오는 글 -> order_num이 더 큰 글 중 가장 작은 것
        return Articles::where('bbs_table_id', $article->bbs_table_id)
            ->where('top', 0)
            ->where('order_num', '>', $article->order_num)
            ->orderBy('order_num', 'asc')
            ->first();
    }

    public static function previous($tableIdOrArticle, $articleId = null)
    {
        $article = ($tableIdOrArticle instanceof Articles)
            ? $tableIdOrArticle
            : Articles::find($articleId);

        if (! $article) {
            return null;
        }

        // 목록(order_num ASC)에서 현재 글 이전(위)에 나오는 글 -> order_num이 더 작은 글 중 가장 큰 것
        return Articles::where('bbs_table_id', $article->bbs_table_id)
            ->where('top', 0)
            ->where('order_num', '<', $article->order_num)
            ->orderBy('order_num', 'desc')
            ->first();
    }

    /**
     * @param  string  $file  bbs/5/202610/37/filename.jpeg 또는 public/bbs/...
     */
    public static function get_thumb($file, $width = null, $height = null)
    {
        if (! $file) {
            return '';
        }

        // 경로 정규화: 'public/' 접두사 제거
        $cleanPath = ltrim(str_replace('public/', '', $file), '/');

        // 가로/세로 지정 없을 시 원본 public URL 반환
        if ($width == null && $height == null) {
            return \Storage::disk('public')->url($cleanPath);
        }

        if ($width == null) {
            $width = $height;
        } elseif ($height == null) {
            $height = $width;
        }

        $fileName = pathinfo($cleanPath, PATHINFO_BASENAME);
        $fileDir = pathinfo($cleanPath, PATHINFO_DIRNAME);

        // 썸네일 디렉터리 (예: bbs/1/202610/12/100_100)
        $thumbDir = $fileDir.'/'.$width.'_'.$height;
        $thumbStoragePath = \Storage::disk('public')->path($thumbDir);
        $thumbFilePath = $thumbStoragePath.'/'.$fileName;

        // 썸네일 파일이 없으면 생성
        if (! file_exists($thumbFilePath)) {
            $originalFilePath = \Storage::disk('public')->path($cleanPath);

            if (file_exists($originalFilePath)) {
                if (! file_exists($thumbStoragePath)) {
                    @mkdir($thumbStoragePath, 0755, true);
                }

                try {
                    $image = new \Pondol\Image\GetHttpImage;
                    $result = $image->read($originalFilePath)->set_size($width, $height)->copyimage();
                    if ($result) {
                        $result->save($thumbStoragePath);
                    }
                } catch (\Exception $e) {
                    \Log::error('BBS Thumbnail Error: '.$e->getMessage());
                }
            } else {
                return '';
            }
        }

        return \Storage::disk('public')->url($thumbDir.'/'.$fileName);
    }
}

class ParamData
{
    public $enc = '';

    public $dec = '';

    public $default = '';
}
