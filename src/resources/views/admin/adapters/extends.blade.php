{{-- 1. 호스트 관리자 레이아웃을 상속 (@extends) --}}
@extends(config('pondol-bbs.admin.layout.name', 'admin.layouts.admin'))

{{-- 2. 타이틀 섹션 전달 --}}
@section(config('pondol-bbs.admin.layout.title_section', 'title'), $title ?? '게시판 관리')

{{-- 3. 본문 섹션에 $slot 주입 (호스트의 @yield(section) 위치로 들어감) --}}
@section(config('pondol-bbs.admin.layout.section', 'content'))
    {{-- LNB(사이드바)가 지정되어 있고 파일이 존재할 경우 주입 --}}
    @if (!empty($lnb) && View::exists($lnb))
        @include($lnb)
    @endif

    {{-- 본문 출력 --}}
    {!! $slot !!}
@endsection
