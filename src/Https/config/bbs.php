<?php

return [
//if you have differnt User Class you should change users and user according to your circumstance
//   print_r(app(config('bbs.users'))).PHP_EOL;
// config('bbs.user')
'users' => App\User::class,
'user' => 'App\User',
];