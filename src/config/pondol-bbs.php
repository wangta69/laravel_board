<?php

// src/config/pondol-bbs.php
return [
    'allowed_images' => ['png', 'jpg', 'jpeg', 'gif', 'bmp'],
    'route_front' => [
        'prefix' => 'bbs',
        'as' => 'bbs.',
        'middleware' => ['web'],
    ],
    'route_admin' => [
        'prefix' => 'bbs/admin',
        'as' => 'bbs.admin.',
        'middleware' => ['web', 'admin'],
    ],
    'route_api' => [
        'prefix' => 'api/v1/bbs',
        'as' => 'bbs.api.',
        'middleware' => [],
    ],
    'login_route_name' => 'login',
    'admin_roles' => 'administrator',

    // [관리자 레이아웃 스마트 설정]
    // 1) 'admin.layouts.admin' : 호스트 앱의 @extends 뷰에 자동 임베드 (예: koption)
    // 2) 'pondol-common::common-admin' : 호스트 앱의 컴포넌트에 자동 임베드 (예: gilra.kr)
    // 3) null : 호스트 앱에 관리자가 없을 때 패키지 내장 레이아웃으로 단독 실행
    'admin' => [
        'layout' => [
            // 대상 레이아웃 이름 (컴포넌트명 또는 뷰 경로, 미사용 시 null)
            'name' => 'pondol-common::common-admin',

            // LNB (사이드바 메뉴 템플릿, 호스트 자체 LNB를 쓸 땐 null)
            'lnb' => 'bbs::lnb-partial',

            // @extends 방식일 때 본문이 들어갈 섹션 이름 (기본값: content)
            'section' => 'content',

            // @extends 방식일 때 타이틀이 들어갈 섹션 이름 (기본값: title)
            'title_section' => 'title',
        ],
    ],

    // (기존 구버전 코드 호환용)
    'component' => [
        'admin' => [
            'layout' => 'pondol-common::common-admin',
            'lnb' => 'bbs::lnb-partial',
        ],
    ],
];
