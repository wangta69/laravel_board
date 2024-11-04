<?php
Route::get('/{tbl_name}/{article}/view', 'BbsController@viewApi');
Route::get('/{tbl_name}', 'BbsController@indexApi');
