<?php
return [
  'admin' => [
    'title' => '게시판 리스트',
    'layout' => '관리자 환경 설정',
    'layout-extends' => '관리자용 Blade Extends',
    'layout-section' => '관리자용 contents section',
    'configure' => '환경설정',
    'bbs'=>[
      'edit'=>'게시판 수정',
      'create'=>'게시판 생성',
      'title'=>'게시판 Title',
      'name'=>'게시판 Name',
      'skin-user'=>'게시판 스킨(회원용)',
      'skin-admin'=>'게시판 스킨(관리자용)',
      'blade-extends'=>'Blade Extends',
      'blade-section'=>'Blade Section',
      'editor'=>'에디터',
      'auth-list'=>'리스트접근권한',
      'auth-read'=>'읽기접근권한',
      'auth-write'=>'쓰기접근권한',
      'a-none'=>'비회원',
      'a-login'=>'일반회원',
      'a-role'=>'특정회원',
      'option'=>'옵션',
      'enable-reply'=>'댓글활성 활성',
      'enable-comment'=>'코멘트 활성',
      'enable-qna'=>'1:1 활성',
      'enable-password'=>'패스워드 활성',
      'category'=>'카테고리',
      'category-name'=>'카테고리명',
      'category-message'=>'카테고리를 등록해 주세요',
      'lists-per-page'=>'페이지당 게시물 수',
    ]
  ],
  'button' => [
    'create' => '생성',
    'update' => '업데이트',
    'edit' => '수정',
    'view' => '보기',
    'delete' => '삭제'
  ],
  
  'message' => [
    'LOGIN' => '로그인 후 이용가능합니다.',
    'confirm-delete' => '삭제하시겠습니까?',
    'deleted-content' => '삭제된 내용입니다.'
  ],
  'bbs'=>[
    'title'=>[
      'number' => '번호',
      'title' => '제목',
      'content' => '내용',
      'created_at' => '작성일',
      'writer' => '작성자',
      'views' => '조회수',
      'attached' => '첨부파일',
      'keywords' => '키워드',
      'no-data' => '디스플레이할 데이타가 없습니다.',
      'comment-placeholder' => '댓글을 입력해 주세요.',
      'comments' => '댓글목록.',
      'title_content' => '제목 + 내용',
      'status' => [
        'answerd' => '답변완료',
        'ready' => '대기중',
      ]
    ],
    'button'=>[
      'create' => '글쓰기',
      'write' => '글쓰기',
      'store' => '작성완료',
      'search' => '검색',
      'modify' => '수정',
      'update' => '수정',
      'delete' => '삭제',
      'list' => '목록',
      'cancel' => '취소',
      'reple-create' => '댓글',
    ]
  ]
];
