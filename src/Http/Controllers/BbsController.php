<?php

namespace Pondol\Bbs\Http\Controllers;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\Contracts\BbsMetaResolver;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Models\BbsFiles as Files;
use Pondol\Bbs\Traits\BbsTrait;
use Pondol\Meta\Facades\Meta;
use Response;
use Storage;

class BbsController extends Controller
{
    use BbsTrait;

    protected $bbsSvc;

    protected $cfg;

    protected $laravel_ver;

    public function __construct(BbsService $bbsSvc)
    {
        $this->bbsSvc = $bbsSvc;
        $laravel = app();
        $this->laravel_ver = $laravel::VERSION;
    }

    /*
    * List Page
    *
    * @param String $tbl_name
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $tbl_name)
    {
        $result = $this->_index($request, $tbl_name); // ['error', 'articles', 'top_articles', 'cfg']

        if (! $result['error']) {
            // 컨테이너에서 리졸버를 가져와 실행 (없으면 폴백)
            $resolver = app()->bound(BbsMetaResolver::class)
                        ? app(BbsMetaResolver::class)
                        : new \Pondol\Bbs\Services\DefaultMetaResolver;
            $cfg = $result['cfg'];
            $articles = $result['articles'];
            $meta = $resolver->resolve(Meta::get(), 'index', $cfg, null, $articles);
            $top_articles = $result['top_articles'];

            // [수정] bbs. -> bbs:: 로 네임스페이스 적용
            return view('bbs::templates.user.'.$cfg->skin.'.index', compact(
                'result',   // 기존 스킨 호환용 (사용자들이 쓰던 {{ $result['article'] }} 유지)
                'cfg',      // 신규 방식용
                'meta',     // 분리된 메타 변수
                'articles',  // 기존 스킨 호환용
                'top_articles' // 상단 게시글 변수
            ));
        } else {
            return $this->errorHandle($result);
        }
    }

    /*
     * Write Form Page
     *
     * @param String $tbl_name
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $tbl_name)
    {
        $result = $this->_create($request, $tbl_name);

        // [수정] bbs. -> bbs:: 로 네임스페이스 적용
        return view('bbs::templates.user.'.$result['cfg']->skin.'.create', $result);
    }

    /*
    * Store to BBS
    *
    * @param String $tbl_name
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request, $tbl_name)
    {
        $result = $this->_store($request, $tbl_name);

        if ($result['error']) {
            // AJAX 요청일 때는 에러 내용도 JSON으로 반환!
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($result, 200);
            }

            return $this->errorHandle($result);
        }

        // AJAX 요청일 때는 성공 데이터(['error' => false, 'article' => ...])를 JSON으로 반환!
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result, 200);
        }

        return redirect()->route('bbs.show', [$result['tbl_name'], $result['article']->id]);
    }

    /*
    * Modify Form
    * @param String $tbl_name
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_edit($request, $tbl_name, $article);
        if ($result['error']) {
            return redirect()->route('bbs.index', [$tbl_name]);
        } else {
            // [수정] bbs. -> bbs:: 로 네임스페이스 적용
            return view('bbs::templates.user.'.$result['cfg']->skin.'.create', $result);
        }
    }

    public function update(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_update($request, $tbl_name, $article);
        if ($result['error']) {
            switch ($result['error']) {
                case 'login':
                    return redirect()->route('login');
                    break;
                case 'NotAuthenticated':
                    return redirect()->back()->withErrors(['NotAuthenticated' => 'Not Authenticated']);
                    break;
                case 'validation':
                    return redirect()->back()->withErrors($result['errors']);
                    break;
            }
        } else {
            return redirect()->route('bbs.show', [$result['tbl_name'], $result['article']->id]);
        }
    }

    public function destroy(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_destroy($request, $tbl_name, $article);

        if ($request->ajax()) {
            return response()->json($result, 200); // 500, 203
        } else {
            return redirect()->route('bbs.index', [$tbl_name]);
        }
    }

    /*
    * Show Article
    *
    * @param String $tbl_name
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_show($request, $tbl_name, $article); // ['error', 'article', 'cfg', 'isAdmin'];

        if ($result['error']) {
            if ($result['error'] == 'password') {
                // [수정] bbs. -> bbs:: 로 네임스페이스 적용
                return view('bbs::templates.user.'.$result['cfg']->skin.'.password-confirm', $result);
            } else {
                return $this->errorHandle($result);
            }
        } else {
            if ($request->ajax()) {
                return response()->json([$result['article']], 200); // 500, 203
            } else {
                $article = $result['article'];
                $cfg = $result['cfg'];
                $isAdmin = $result['isAdmin'];
                $resolver = app()->bound(BbsMetaResolver::class)
                    ? app(BbsMetaResolver::class)
                    : new \Pondol\Bbs\Services\DefaultMetaResolver;

                $meta = $resolver->resolve(Meta::get(), 'show', $cfg, $article);

                // [수정] bbs. -> bbs:: 로 네임스페이스 적용
                return view('bbs::templates.user.'.$cfg->skin.'.show', compact(
                    'result',   // 기존 스킨 호환용 (사용자들이 쓰던 {{ $result['article'] }} 유지)
                    'article',  // 신규 방식용
                    'cfg',      // 신규 방식용
                    'isAdmin',  // 신규 방식용
                    'meta'      // 분리된 메타 변수
                ));
            }
        }
    }

    public function passwordConfirm(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_passwordConfirm($request, $tbl_name, $article);

        if (isset($result['error'])) {
            if ($result['error'] == 'validation') {
                return redirect()->back()->withInput()->withErrors($result['errors']);
            }
        }

        return redirect()->route('bbs.show', [$tbl_name, $article->id]);
    }

    public function comment(Request $request, $tbl_name, Articles $article)
    {
        $comment = $article->comment[0];
        $comment->content = htmlentities($comment->content);
        if ($request->ajax()) {
            return response()->json([$comment], 200); // 500, 203
        }
    }

    /**
     * file download from storage
     */
    public function download(Files $file)
    {
        $result = $this->_download($file);
        if ($result['error']) {
            return $this->errorHandle($result);
        } else {
            return Response::download($result['file_path'], $result['file']->file_name, [
                'Content-Length: '.filesize($result['file_path']),
            ]);
        }
    }

    public function deletFile(Request $request, Files $file)
    {
        $relativePath = ltrim(str_replace('public/', '', $file->path_to_file), '/');

        if (\Storage::disk('public')->exists($relativePath)) {
            \Storage::disk('public')->delete($relativePath);
        }

        $file->delete();

        return response()->json(['error' => false, 'file' => $file], 200);
    }

    private function errorHandle($result)
    {
        switch ($result['error']) {
            case 'validation':
                return redirect()->back()->withErrors($result['errors'])->withInput($result['input']);
                break;
            case 'login':
                return redirect()->route(config('pondol-bbs.login_route_name'));
                break;
        }
    }
}
