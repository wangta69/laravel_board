<?php

return [
  'allowed_images' => ['png', 'jpg', 'jpeg', 'gif', 'bmp'],
  'route_front' => [
    'prefix' => 'bbs',
    'as' => 'bbs.',
    'middleware' => ['web']
  ],
  'route_admin' => [
    'prefix' => 'bbs/admin',
    'as' => 'bbs.admin.',
    'middleware' => ['web', 'admin']
  ],
  'route_api' => [
    'prefix' => 'api/v1/bbs',
    'as' => 'bbs.api.',
    'middleware' => []
  ],
  'login_route_name' => 'login',
  'admin_roles'=>'administrator'
];
