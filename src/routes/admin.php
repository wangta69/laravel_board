<?php
Route::get('', 'AdminController@index')->name('index');

// 환경설정
Route::put('config/update', 'AdminController@configUpdate')->name('config.update');

Route::get('create', 'AdminController@createForm')->name('create');
Route::post('create', 'AdminController@store')->name('store');
Route::put('{table}/create', 'AdminController@update')->name('update');

Route::get('{table}/create', 'AdminController@createForm')->name('show');  
Route::delete('{table}/delete', 'AdminController@destroy')->name('destroy');


// 캬테고리 관련
Route::post('category/add/{table}', 'CategoryController@addCategory')->name('category.store');
Route::put('category/update/{category}/{direction}', 'CategoryController@updateOrder')->name('category.update');
Route::delete('category/delete/{category}', 'CategoryController@deleteCategory')->name('category.destroy');

// 관리자에서 게시물 핸들링
Route::get('tbl/{tbl_name}', 'BbsController@index')->name('tbl.index');
Route::get('tbl/{tbl_name}/{article}/show', 'BbsController@show')->name('tbl.show');
Route::get('tbl/{tbl_name}/create', 'BbsController@create')->name('tbl.create');
Route::post('tbl/{tbl_name}/store', 'BbsController@store')->name('tbl.store');
Route::put('tbl/{tbl_name}/{article}/update', 'BbsController@update')->name('tbl.update');
Route::get('tbl/{tbl_name}/{article}/edit', 'BbsController@edit')->name('tbl.edit');
Route::delete('tbl/{tbl_name}/{article}/destroy', 'BbsController@destroy')->name('tbl.destroy');
Route::get('tbl/{file_id}/download', 'BbsController@download')->name('tbl.download');

Route::post('tbl/{tbl_name}/{article}/comment/{comment}', 'CommentController@store')->name('tbl.comment.store');
Route::put('tbl/{tbl_name}/{article}/comment/{comment}', 'CommentController@update')->name('tbl.comment.update');
Route::delete('tbl/{tbl_name}/{article}/comment/{comment}', 'CommentController@destroy')->name('tbl.comment.destroy');

// 아이템코멘트 
Route::get('item-comment/{item}', 'ItemCommentController@index')->name('comments');

