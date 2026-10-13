<?php

namespace Pondol\Bbs\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pondol\Bbs\BbsService;
use Pondol\Bbs\Models\BbsArticles as Articles;
use Pondol\Bbs\Traits\BbsTrait;

class BbsController extends Controller
{
    use BbsTrait;

    public function __construct(BbsService $bbsSvc)
    {
        $this->bbsSvc = $bbsSvc;
    }

    /**
     * 게시물 리스트
     */
    public function index(Request $request, $tbl_name)
    {
        $result = $this->_index($request, $tbl_name);

        if (isset($result['error'])) {
            if ($result['error'] == 'login') {
                return redirect()->route('login');
            }
        }

        // [수정] bbs. -> bbs:: 로 네임스페이스 적용
        return view('bbs::templates.admin.'.$result['cfg']->skin_admin.'.index', $result);
    }

    /**
     * 게시물 보기
     */
    public function show(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_show($request, $tbl_name, $article);

        if (isset($result['error'])) {
            if ($result['error'] == 'password') {
                // [수정] bbs. -> bbs:: 로 네임스페이스 적용
                return view('bbs::templates.admin.'.$result['cfg']->skin_admin.'.password-confirm', [
                    'tbl_name' => $tbl_name,
                    'article' => $article->id,
                ]);
            }
        }

        // [수정] bbs. -> bbs:: 로 네임스페이스 적용
        return view('bbs::templates.admin.'.$result['cfg']->skin_admin.'.show', $result);
    }

    public function create(Request $request, $tbl_name)
    {
        $result = $this->_create($request, $tbl_name);

        // [수정] bbs. -> bbs:: 로 네임스페이스 적용
        return view('bbs::templates.admin.'.$result['cfg']->skin_admin.'.create', $result);
    }

    public function store(Request $request, $tbl_name)
    {
        $result = $this->_store($request, $tbl_name);

        if ($result['error']) {
            return $this->errorHandle($result);
        }

        return redirect()->route('bbs.admin.tbl.show', [$result['tbl_name'], $result['article']->id]);
    }

    public function edit(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_edit($request, $tbl_name, $article);

        // [수정] bbs. -> bbs:: 로 네임스페이스 적용
        return view('bbs::templates.admin.'.$result['cfg']->skin_admin.'.create', $result);
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
            return redirect()->route('bbs.admin.tbl.show', [$result['tbl_name'], $result['article']->id]);
        }
    }

    public function destroy(Request $request, $tbl_name, Articles $article)
    {
        $result = $this->_destroy($request, $tbl_name, $article);

        if ($request->ajax()) {
            return response()->json($result, 200);
        } else {
            return redirect()->route('bbs.admin.tbl.index', [$tbl_name]);
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

        return redirect()->route('bbs.admin.tbl.show', [$tbl_name, $article->id]);
    }

    private function errorHandle($result)
    {
        switch ($result['error']) {
            case 'validation':
                return redirect()->back()->withInput()->withErrors($result['errors']);
                break;
            case 'login':
                return redirect()->route(config('pondol-bbs.login_route_name'));
                break;
        }
    }
}
