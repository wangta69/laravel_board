<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'Pondol\Bbs', 'middleware' => ['web']], function () {
  Route::get('/route-url', 'ServiceController@routeUrl');
});

Route::group(['prefix' => 'bbs/admin', 'as' => 'bbs.admin.', 'namespace' => 'App\Http\Controllers\Bbs\Admin', 'middleware' => ['web']], function () {
  Route::get('', 'AdminController@index')->name('index');
  
  // 환경설정
  Route::put('config/update', 'AdminController@configUpdate')->name('config.update');
  
  Route::get('create', 'AdminController@createForm')->name('create');
  Route::post('create', 'AdminController@store')->name('store');
  Route::put('{table}/create', 'AdminController@update')->name('update');

  Route::get('{table}/create', 'AdminController@createForm')->name('show');  
  Route::delete('{table}/delete', 'AdminController@destroy')->name('destroy');
  

  // 캬테고리 관련
  Route::post('category/add/{table}', 'CategoryController@addCategory');
  Route::put('category/update/{category}/{direction}', 'CategoryController@updateOrder');
  Route::delete('category/delete/{category}', 'CategoryController@deleteCategory');

  // 관리자에서 게시물 핸들링
  Route::get('tbl/{tbl_name}', 'BbsController@_index')->name('tbl.index');
  Route::get('tbl/{tbl_name}/{article}/show', 'BbsController@_show')->name('tbl.show');
  Route::get('tbl/{tbl_name}/create', 'BbsController@_create')->name('tbl.create');
  Route::post('tbl/{tbl_name}/store', 'BbsController@_store')->name('tbl.store');
  Route::put('tbl/{tbl_name}/{article}/store', 'BbsController@_update')->name('tbl.update');
  Route::get('tbl/{tbl_name}/{article}/edit', 'BbsController@_edit')->name('tbl.edit');
  Route::delete('tbl/{tbl_name}/{article}/destroy', 'BbsController@_destroy')->name('tbl.destroy');
  Route::get('tbl/{file_id}/download', 'BbsController@_download')->name('tbl.download');

  Route::post('tbl/{tbl_name}/{article}/comment/{comment}', 'CommentController@_store')->name('tbl.comment.store');
  Route::put('tbl/{tbl_name}/{article}/comment/{comment}', 'CommentController@_update')->name('tbl.comment.update');
  Route::delete('tbl/{tbl_name}/{article}/comment/{comment}', 'CommentController@_destroy')->name('tbl.comment.destroy');

  // 아이템코멘트 
  Route::get('item-comment/{item}', 'ItemCommentController@_index')->name('comments');
});


// Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {

//     Route::get('/{file_id}/download', 'BbsController@download')->name('download');
//     Route::get('/{tbl_name}/create', 'BbsController@createForm')->name('create');
//     Route::post('/{tbl_name}/store', 'BbsController@store')->name('store');
//     Route::put('/{tbl_name}/{article}/store', 'BbsController@update')->name('update');
//     Route::get('/{tbl_name}/{article}/edit', 'BbsController@editForm')->name('edit');
//     Route::delete('/{tbl_name}/{article}/destroy', 'BbsController@destroy')->name('destroy');
//     Route::get('/{tbl_name}/{article}/show', 'BbsController@show')->name('show');
//     Route::get('/{tbl_name}/{article}/first-comment', 'BbsController@comment')->name('firstcomment'); // for faq board
//     Route::get('/{tbl_name}', 'BbsController@index')->name('index');
//     //Route::post('/{tbl_name}/{article}/comment/store', 'BbsCommentController@store')->name('comment.store');
// });

Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {
  
  Route::get('/file/{file}', 'BbsController@_download')->name('file.download');
  Route::delete('/file/{file}', 'BbsController@deletFile')->name('file.delete');
  
 
  Route::get('/{tbl_name}', 'BbsController@_index')->name('index');
  Route::get('/{tbl_name}/{article}/show', 'BbsController@_show')->name('show');
  // Route::get('bbs/{tbl_name}/{article}/show/confirm', 'BBSController@_passwordConfirm')->name('bbs.show.password');
  Route::post('/{tbl_name}/{article}/show/confirm', 'BbsController@_passwordConfirm')->name('show.password');
  Route::get('/{tbl_name}/create', 'BbsController@_create')->name('create');
  Route::post('/{tbl_name}/store', 'BbsController@_store')->name('store');

  Route::get('/{tbl_name}/{article}/edit', 'BbsController@_edit')->name('edit');
  Route::put('/{tbl_name}/{article}/store', 'BbsController@_update')->name('update');
  Route::delete('/{tbl_name}/{article}/destroy', 'BbsController@_destroy')->name('destroy');
});




Route::group(['prefix' => 'bbs', 'as' => 'bbs.comment.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {
  Route::post('/{tbl_name}/{article}/comment/{comment}', 'CommentController@_store')->name('store');
  Route::delete('/{tbl_name}/{article}/comment/{comment}', 'CommentController@_destroy')->name('destroy');
  Route::put('/{tbl_name}/{article}/comment/{comment}', 'CommentController@_update')->name('update');
});

Route::group(['prefix' => 'bbs', 'as' => 'bbs.item.comment.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {
 
  Route::post('/item-comment/{item}/{item_id}/{parent_id?}', 'ItemCommentController@_store')->name('store');
  // Route::get('/item-comment/{item}/{item_id}/{parent_id?}', 'BbsItemCommentController@_store')->name('store');
  //  Route::get('/{tbl_name}/{article}/comment/{comment}', 'BbsCommentController@store')->name('store');//for test
  // Route::delete('/item-comment/{item}/{parent}/{comment}', 'BbsItemCommentController@_destroy')->name('destroy');
  // Route::put('/item-comment/{item}/{item_id}/{parent}', 'BbsItemCommentController@_update')->name('update');

});