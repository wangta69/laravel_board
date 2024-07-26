<?php
return [
  'admin' => [
    'title' => 'BBS List',
    'layout' => 'Layout setting for admin',
    'layout-extends' => 'Blade extends for admin',
    'layout-section' => 'Contents section for admin',
    'configure' => 'BBS configure',
    'bbs'=>[
      'edit'=>'BBS Edit',
      'create'=>'BBS Create',
      'title'=>'BBS Title',
      'name'=>'BBS Name',
      'skin-user'=>'Template for users',
      'skin-admin'=>'Template for admin.',
      'blade-extends'=>'Blade Extends',
      'blade-section'=>'Blade Section',
      'editor'=>'Editor',
      'auth-list'=>'Auth for list',
      'auth-read'=>'Auth for read',
      'auth-write'=>'Auth for write',
      'a-none'=>'Guest',
      'a-login'=>'Auth',
      'a-role'=>'Certen Auth',
      'option'=>'Option',
      'enable-reply'=>'Enable Reply',
      'enable-comment'=>'Enable Comment',
      'enable-qna'=>'Enable Qna',
      'enable-password'=>'Enable password',
      'category'=>'Category',
      'category-name'=>'Category name',
      'category-message'=>'Pls. register category',
      'lists-per-page'=>'List count per page',
    ]
  ],
  'button' => [
    'create' => 'Create',
    'update' => 'Udate',
    'edit' => 'Edit',
    'view' => 'View',
    'delete' => 'Delete'
  ],
  'message' => [
    'LOGIN' => 'Available after login.',
    'confirm-delete' => 'Are you sure you want to delete?',
    'deleted-content' => 'This content was deleted'
  ],
  'bbs'=>[
    'title'=>[
      'number' => 'No.',
      'title' => 'Title',
      'content' => 'Content',
      'created_at' => 'Created',
      'writer' => 'Writer',
      'views' => 'Views',
      'attached' => 'Files',
      'no-data' => 'There is no data to display.',
      'comment-placeholder' => 'Comment...',
      'comments' => 'Comments.',
      'status' => [
        'answerd' => 'Answerd',
        'ready' => 'Ready',
      ]
    ],
    'button'=>[
      'create' => 'Write',
      'write' => 'Write',
      'store' => 'Store',
      'search' => 'Search',
      'modify' => 'Modify',
      'update' => 'Update',
      'delete' => 'Delete',
      'list' => 'List',
      'cancel' => 'Cancel',
      'reple-create' => 'Write',
    ]
  ]
];
