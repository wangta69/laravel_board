<?php

namespace Pondol\Bbs\Traits;

use Auth;
use File;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\Events\ArticleCreated;
use Pondol\Bbs\Events\ArticleDeleted;
use Pondol\Bbs\Events\ArticleUpdated;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsFiles as Files;
use Storage;
use Validator;

trait BbsTrait
{
    /*
     * List Page
     *
     * @param String $tbl_name
     * @return \Illuminate\Http\Response
     */
    public function _index($request, $tbl_name)
    {
        $preIndex = $this->preIndex($tbl_name);
        $articles = $preIndex->articles;
        $cfg = $preIndex->cfg;

        // [외부 쿼리 커스터마이저 실행]
        if (app()->bound(\Pondol\Bbs\Contracts\BbsQueryCustomizer::class)) {
            $articles = app(\Pondol\Bbs\Contracts\BbsQueryCustomizer::class)
                ->indexQuery($articles, $cfg, $request);
        }

        $top_articles = Articles::where('bbs_table_id', $preIndex->cfg->id);

        $user = $request->user();
        if ($cfg->auth_list === 'login' && ! $user) {
            return ['error' => 'login'];
        }

        $f = $request->input('f', null); // 검색 필드
        $s = trim($request->input('s', '')); // 검색어

        // [고도화 1] 검색 필드 화이트리스트 및 검색 쿼리 안정화
        $allowedFields = ['title', 'content', 'writer'];
        if ($s !== '') {
            if ($f && in_array($f, $allowedFields)) {
                $articles->where($f, 'like', '%'.$s.'%');
            } else {
                // 전체 검색: 제목 또는 내용 Like 검색 (길이 제약 없이 가장 안정적)
                $articles->where(function ($q) use ($s) {
                    $q->where('title', 'like', '%'.$s.'%')
                        ->orWhere('content', 'like', '%'.$s.'%');
                });
            }
        }

        // [고도화 2] 1:1 QnA 모드일 때 본인 글만 필터링
        if ($cfg->enable_qna == '1') {
            $adminrole = config('pondol-bbs.admin_roles', 'administrator');
            $hasrole = BbsService::hasRoles($adminrole);
            if (! $hasrole) {
                if (! $user) {
                    return ['error' => 'login'];
                }
                $articles = $articles->where('user_id', $user->id);
            }
        }

        // 상단 공지 및 일반 글 정렬
        $articles->orderBy('order_num', 'asc')->where('top', 0);
        $top_articles = $top_articles->where('top', 1)->orderBy('order_num', 'asc')->get();

        $articles = $articles->paginate($cfg->lists)->appends($request->query());

        return ['error' => false, 'articles' => $articles, 'top_articles' => $top_articles, 'cfg' => $cfg];
    }

    /**
     * index를 가져올 전처리 작업 (select 등 다양한 경우에 대비하기위해 index를 가져오기 전에 먼저 선 작업을 한다.)
     */
    private function preIndex($tbl_name)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        // [방어 추가] 존재하지 않는 게시판명이면 즉시 404 반환 (500 에러 원천 차단)
        if (! $cfg) {
            abort(404, '존재하지 않는 게시판입니다.');
        }

        $obj = new \stdClass;
        $obj->cfg = $cfg;
        $obj->articles = Articles::where('bbs_table_id', $cfg->id);

        return $obj;
    }

    /**
     * API 호출시 직접 데이타 처리
     */
    public function _indexApi($request, $tbl_name)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        if (! $cfg) {
            return response()->json(['error' => 'NOT_FOUND_BOARD', 'message' => '존재하지 않는 게시판입니다.'], 404);
        }

        $articles = Articles::where('bbs_table_id', $cfg->id)
            ->orderBy('order_num')
            ->paginate($cfg->lists)
            ->appends(request()->query());

        return response()->json(['error' => false, 'articles' => $articles, 'cfg' => $cfg], 200);

    }

    /*
     * Write Form Page
     *
     * @param String $tbl_name
     * @return \Illuminate\Http\Response
     */
    public function _create($request, $tbl_name)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        if (! $cfg) {
            abort(404, '존재하지 않는 게시판입니다.');
        }

        // check permission
        $permission_result = $cfg->hasPermission('write');
        if (! $permission_result) {
            abort(403, 'Unauthorized action.');
        }

        return ['error' => false, 'cfg' => $cfg, 'article' => new Articles];
    }

    private function storeOrUpdateValidation($request, $tbl_name)
    {
        $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        // check permission
        $permission_result = $cfg->hasPermission('write');
        if (! $permission_result) {
            return ['error' => 'NotAuthenticated'];
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2|max:100',
        ], [
            'title.*' => '2글자 이상의 제목을 입력해 주세요',
            'content.required' => '내용을 입력해 주세요',
            'password.required' => '패스워드를 입력해 주세요',
        ]);

        $validator->sometimes('content', 'required', function ($input) use ($request) {
            return ! $request->no_content;
        });

        if (! $isAdmin) {
            $validator->sometimes('password', 'required', function ($input) use ($cfg) {
                return $cfg->enable_password == 1;
            });
        }

        if ($validator->fails()) {
            return ['error' => 'validation', 'errors' => $validator->errors(), 'input' => $request->all()];
        }

        return ['error' => false, 'cfg' => $cfg];
    }

    private function storeOrUpdate($request, $article)
    {
        $article->title = $request->get('title');
        $article->writer = $request->get('writer') ?? $article->writer;
        $article->password = $request->get('password');
        $article->content = $request->get('content');
        $article->text_type = $request->input('text_type', 'br');
        $article->keywords = $request->input('keywords');
        $article->bbs_category_id = $request->get('category');
        $article->top = $request->input('top', 0);

        return $article;
    }

    private function storeOrUpdateTrimContents($request, $article, $cfg)
    {
        $date_Ym = date('Ym', strtotime($article->created_at));
        $directory = 'bbs/'.$cfg->id.'/'.$date_Ym.'/'.$article->id;

        if (is_array($request->file('uploads'))) {
            foreach ($request->file('uploads') as $index => $upload) {
                if ($upload == null) {
                    continue;
                }

                // 기존 파일이 있으면 Storage에서 삭제 후 교체
                if (($file = $article->files->where('rank', $index)->first())) {
                    $oldPath = ltrim(str_replace('public/', '', $file->path_to_file), '/');
                    if (\Storage::disk('public')->exists($oldPath)) {
                        \Storage::disk('public')->delete($oldPath);
                    }
                    $file->delete();
                }

                $filename = $upload->getClientOriginalName();
                $path = \Storage::disk('public')->putFileAs($directory, $upload, $filename);

                if ($path) {
                    $file = new Files;
                    $file->rank = $index;
                    $file->bbs_articles_id = $article->id;
                    $file->file_name = $filename;
                    $file->path_to_file = $path; // bbs/1/202610/12/example.png
                    $file->name_on_disk = basename($path);
                    $file->save();
                }
            }
        }

        $this->contents_update($article, $cfg->id, $date_Ym);
        $this->set_representaion($article);
    }

    /*
      * Store to BBS
      *
      * @param String $tbl_name
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
    public function _store($request, $tbl_name)
    {
        $validation = $this->storeOrUpdateValidation($request, $tbl_name);
        if ($validation['error']) {
            return $validation;
        }

        $article = new Articles;
        $article = $this->storeOrUpdate($request, $article);

        $article->bbs_table_id = $validation['cfg']->id;

        if (Auth::check()) {
            $article->user_id = Auth::user()->id;
            $article->writer = $article->writer ?? Auth::user()->name;
        } else {
            $article->user_id = 0;
        }

        $article->order_num = $this->get_order_num();
        $article->parent_id = 0;
        $article->comment_cnt = 0;
        $article->save();
        $article->parent_id = $request->get('parent_id') ?? $article->id;
        $article->save();

        if ($article->password) {
            $auth_key = "bbs.auth.{$tbl_name}.{$article->id}";
            session()->put($auth_key, true);
        }

        $this->storeOrUpdateTrimContents($request, $article, $validation['cfg']);

        // [이벤트 발행] 새 글 등록 완료 이벤트 발생
        event(new ArticleCreated($article, $validation['cfg'], Auth::user()));

        return ['error' => false, 'tbl_name' => $tbl_name, 'article' => $article];
    }

    /*
     * Modify Article
     *
     * @param  \Illuminate\Http\Request  $request
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function _update($request, $tbl_name, $article)
    {
        $validation = $this->storeOrUpdateValidation($request, $tbl_name);
        if ($validation['error']) {
            return $validation;
        }

        $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
        if (! $article->isOwner(Auth::user()) && ! $isAdmin) {
            return ['error' => 'NotAuthenticated'];
        }

        $article = $this->storeOrUpdate($request, $article);
        $article->save();
        $this->storeOrUpdateTrimContents($request, $article, $validation['cfg']);

        // [이벤트 발행] 글 수정 완료 이벤트 발생
        event(new ArticleUpdated($article, $validation['cfg'], Auth::user()));

        return ['error' => false, 'tbl_name' => $tbl_name, 'article' => $article];
    }

    protected function get_order_num($params = null)
    {
        $order_num = Articles::min('order_num');

        return $order_num ? $order_num - 1 : -1;
    }

    protected function contents_update($article, $table_id, $date_Ym)
    {
        $sessionId = session()->getId();
        $sourceDir = storage_path('app/public/tmp/editor/'.$sessionId);
        $destinationDir = storage_path('app/public/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor');

        if (File::exists($sourceDir)) {
            $article->content = str_replace(
                '/storage/tmp/editor/'.$sessionId,
                '/storage/bbs/'.$table_id.'/'.$date_Ym.'/'.$article->id.'/editor',
                $article->content
            );
            $article->save();

            File::copyDirectory($sourceDir, $destinationDir);
            \Storage::disk('public')->deleteDirectory('tmp/editor/'.$sessionId);
        }
    }

    protected function set_representaion($article)
    {
        $article->image = null;
        $imageExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'bmp'];

        // 1순위: 첨부파일 중 이미지
        $files = Files::where('bbs_articles_id', $article->id)->orderBy('rank', 'asc')->get();
        foreach ($files as $file) {
            if ($file->path_to_file) {
                $ext = strtolower(pathinfo($file->path_to_file, PATHINFO_EXTENSION));
                if (in_array($ext, $imageExtensions)) {
                    $article->image = ltrim(str_replace('public/', '', $file->path_to_file), '/');
                    break;
                }
            }
        }

        // 2순위: 본문 에디터 내 이미지
        if (! $article->image && $article->content) {
            preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $article->content, $matches);
            if (! empty($matches[1])) {
                $firstImgSrc = $matches[1][0];

                // 에디터 이미지가 storage 내 경로인 경우
                if (strpos($firstImgSrc, '/storage/') !== false) {
                    $relativePath = ltrim(str_replace('/storage/', '', $firstImgSrc), '/');
                    if (\Storage::disk('public')->exists($relativePath)) {
                        $article->image = $relativePath;
                    }
                }
            }
        }

        $article->save();
    }

    /*
      * Show Article
      *
      * @param String $tbl_name
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
    public function _show($request, $tbl_name, $article)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);
        if (! $cfg) {
            abort(404, '존재하지 않는 게시판입니다.');
        }

        $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles', 'administrator'));
        $user = $request->user();

        // 1. 읽기 권한 검사
        if ($cfg->auth_read === 'login' && ! $user) {
            return ['error' => 'login'];
        }

        // [고도화 3] 1:1 QnA 모드 URL 직접 접근 차단
        if ($cfg->enable_qna == '1' && ! $isAdmin) {
            if (! $user || (int) $article->user_id !== (int) $user->id) {
                abort(403, '작성자 본인만 열람할 수 있는 게시글입니다.');
            }
        }

        // [고도화 4] 세션 키 분리 (비밀번호 인증 키 vs 조회수 증가 키)
        $auth_key = "bbs.auth.{$tbl_name}.{$article->id}";
        $hit_key = "bbs.hit.{$tbl_name}.{$article->id}";

        // 비밀글 접근 통제
        if (! $isAdmin && $article->password && ! session()->has($auth_key)) {
            // 본인 작성글(로그인 회원)인 경우 비밀번호 면제
            if (! ($user && $article->isOwner($user))) {
                return ['error' => 'password', 'cfg' => $cfg, 'tbl_name' => $tbl_name, 'article' => $article];
            }
        }

        // 조회수 중복 증가 방지 및 카운트 증가
        if (! session()->has($hit_key)) {
            $article->increment('hit');
            session()->put($hit_key, true);
        }

        if ($article->text_type === 'br') {
            $article->content = nl2br($article->content);
        }

        return ['error' => false, 'article' => $article, 'cfg' => $cfg, 'isAdmin' => $isAdmin];
    }

    /**
     * 패스워드가 설정되어 있을 경우 패스워드 입력창에서 넘어오는 패스워드를 확인하고 session을 설정
     */
    public function _passwordConfirm($request, $tbl_name, $article)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ], [
            'password.required' => '패스워드를 입력해 주세요',
        ]);

        if (trim($request->password) !== trim($article->password)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('password', '일치하지 않는 패스워드입니다.');
            });

            return ['error' => 'validation', 'errors' => $validator->errors()];
        }

        $auth_key = "bbs.auth.{$tbl_name}.{$article->id}";
        session()->put($auth_key, true);

        return ['error' => false];
    }

    public function _viewApi($tbl_name, $article, $request)
    {
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        $content = Articles::find($article);

        $session_key = "bbs.viewed.{$tbl_name}.{$content->id}";

        if (! session()->has($session_key)) {
            $content->hit++;
            $content->save();

            session()->put($session_key, true);
        }

        return response()->json(['error' => false, 'article' => $content], 200);
    }

    /*
     * Modify Form
     *
     * @param String $tbl_name
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function _edit($request, $tbl_name, $article)
    {
        $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        if ($article->isOwner(Auth::user()) || $isAdmin) {
            return ['error' => false, 'article' => $article, 'cfg' => $cfg];
        } else {
            return ['error' => '권한이 없습니다.'];
        }
    }

    /*
      * Delete Article
      *
      * @param String $tbl_name
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
    public function _destroy($request, $tbl_name, $article)
    {
        $isAdmin = BbsService::hasRoles(config('pondol-bbs.admin_roles'));
        $cfg = $this->bbsSvc->get_table_info_by_table_name($tbl_name);

        if (! $article->isOwner(Auth::user()) && ! $isAdmin) {
            return ['error' => '삭제권한이 없습니다.'];
        }

        // 1. Storage 파일 및 디렉터리 삭제 (disk('public') 사용)
        $directory = 'bbs/'.$cfg->id.'/'.date('Ym', strtotime($article->created_at)).'/'.$article->id;
        \Storage::disk('public')->deleteDirectory($directory);

        // 2. Files DB 삭제
        Files::where('bbs_articles_id', $article->id)->delete();

        // 3. Article 모델 삭제 (SoftDelete)
        $article->delete();

        // [이벤트 발행] 글 삭제 완료 이벤트 발생
        event(new ArticleDeleted($article, $cfg, Auth::user()));

        return ['error' => false];
    }

    /**
     * file download from storage
     */
    public function _download($file)
    {
        $relativePath = ltrim(str_replace('public/', '', $file->path_to_file), '/');

        if (\Storage::disk('public')->exists($relativePath)) {
            $filePath = \Storage::disk('public')->path($relativePath);

            return ['error' => false, 'file_path' => $filePath, 'file' => $file];
        }

        // 레거시 storage/app 직하위 경로 폴백 체크
        $fallbackPath = storage_path('app/'.$file->path_to_file);
        if (file_exists($fallbackPath)) {
            return ['error' => false, 'file_path' => $fallbackPath, 'file' => $file];
        }

        return ['error' => 'Requested file does not exist on our server!'];
    }
}
