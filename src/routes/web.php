<?php
Route::get('/file/{file}', 'BbsController@download')->name('file.download');
Route::delete('/file/{file}', 'BbsController@deletFile')->name('file.delete');


Route::get('/{tbl_name}', 'BbsController@index')->name('index');
Route::get('/{tbl_name}/{article}/show', 'BbsController@show')->name('show');
// Route::get('bbs/{tbl_name}/{article}/show/confirm', 'BBSController@passwordConfirm')->name('bbs.show.password');
Route::post('/{tbl_name}/{article}/show/confirm', 'BbsController@passwordConfirm')->name('show.password');
Route::get('/{tbl_name}/create', 'BbsController@create')->name('create');
Route::post('/{tbl_name}/store', 'BbsController@store')->name('store');

Route::get('/{tbl_name}/{article}/edit', 'BbsController@edit')->name('edit');
Route::put('/{tbl_name}/{article}/store', 'BbsController@update')->name('update');
Route::delete('/{tbl_name}/{article}/destroy', 'BbsController@destroy')->name('destroy');

// comment
Route::post('/{tbl_name}/{article}/comment/{comment}', 'CommentController@store')->name('comment.store');
Route::delete('/{tbl_name}/{article}/comment/{comment}', 'CommentController@destroy')->name('comment.destroy');
Route::put('/{tbl_name}/{article}/comment/{comment}', 'CommentController@update')->name('comment.update');

// item.comment
Route::post('/item-comment/{item}/{item_id}/{parent_id?}', 'ItemCommentController@store')->name('item.comment.store');
// Route::get('/item-comment/{item}/{item_id}/{parent_id?}', 'BbsItemCommentController@store')->name('item.comment.store');
//  Route::get('/{tbl_name}/{article}/comment/{comment}', 'BbsCommentController@store')->name('item.comment.store');//for test
// Route::delete('/item-comment/{item}/{parent}/{comment}', 'BbsItemCommentController@destroy')->name('item.comment.destroy');
// Route::put('/item-comment/{item}/{item_id}/{parent}', 'BbsItemCommentController@update')->name('item.comment.update');
