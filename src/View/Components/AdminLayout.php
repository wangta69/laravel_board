<?php

namespace Pondol\Bbs\View\Components;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class AdminLayout extends Component
{
    public $title;

    public $path;

    public $layoutName;

    public $isExtends = false;

    public function __construct($title = null, $path = [])
    {
        $this->title = $title ?? '게시판 관리';
        $this->path = $path;

        $config = config('pondol-bbs.admin.layout');

        // 설정이 배열이든 문자열이든 유연하게 파싱
        if (is_array($config)) {
            $this->layoutName = $config['name'] ?? 'pondol-common::common-admin';
        } else {
            $this->layoutName = $config ?? 'pondol-common::common-admin';
        }

        // 라라벨에 일반 뷰 파일(예: admin.layouts.admin)로 존재하는지 검사
        // 일반 뷰 파일이 존재하면 @extends 모드로 작동!
        if (View::exists($this->layoutName)) {
            $this->isExtends = true;
        }
    }

    public function render()
    {
        // 1. koption 등 호스트의 @extends 레이아웃일 때:
        // Blade 컴파일 충돌 없이 호스트 레이아웃의 @yield(content)에 본문을 안전하게 주입
        if ($this->isExtends) {
            return function (array $data) {
                $section = config('pondol-bbs.admin.layout.section', 'content');
                $titleSection = config('pondol-bbs.admin.layout.title_section', 'title');

                // 호스트 레이아웃의 섹션에 $slot과 $title 주입
                app('view')->startSection($section);
                echo $data['slot'];
                app('view')->stopSection();

                app('view')->startSection($titleSection);
                echo $this->title;
                app('view')->stopSection();

                return app('view')->make($this->layoutName, [
                    'path' => $this->path,
                    'title' => $this->title,
                ])->render();
            };
        }

        // 2. gilra.kr 등 pondol-common 컴포넌트일 때:
        return view('bbs::components.admin-layout');
    }
}
