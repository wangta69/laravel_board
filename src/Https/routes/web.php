<?php

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
Route::group(['prefix' => 'bbs/admin', 'as' => 'bbs.admin.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {
    Route::get('', 'AdminController@index')->name('index');
    Route::get('create', 'AdminController@createForm')->name('create');
    Route::get('{table}/create', 'AdminController@createForm')->name('show');
    Route::post('create', 'AdminController@store')->name('store');
    Route::put('{table}/create', 'AdminController@update')->name('store');
    Route::delete('{table}/delete', 'AdminController@destroy')->name('destroy');
});

Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {
    Route::post('/plugins/{plugins}/file-upload', 'PluginsController@fileUpload')->name('plugin.fileupload');
    Route::get('/plugins/{plugins}/{action}', 'PluginsController@index')->name('plugin.index');
    Route::get('/plugins/{plugins}', 'PluginsController@index')->name('plugin.index');
    Route::get('/{file_id}/download', 'BbsController@download')->name('download');
    Route::get('/{tbl_name}/create', 'BbsController@createForm')->name('create');
    Route::post('/{tbl_name}/store', 'BbsController@store')->name('store');
    Route::put('/{tbl_name}/{article}/store', 'BbsController@update')->name('update');
    Route::get('/{tbl_name}/{article}/edit', 'BbsController@editForm')->name('edit');
    Route::delete('/{tbl_name}/{article}/destroy', 'BbsController@destroy')->name('destroy');
    Route::get('/{tbl_name}/{article}/show', 'BbsController@show')->name('show');
    Route::get('/{tbl_name}', 'BbsController@index')->name('index');
    //Route::post('/{tbl_name}/{article}/comment/store', 'BbsCommentController@store')->name('comment.store');
});

Route::group(['prefix' => 'bbs', 'as' => 'bbs.comment.', 'namespace' => 'Pondol\Bbs', 'middleware' => ['web']], function () {
    Route::post('/{tbl_name}/{article}/comment/{comment}', 'BbsCommentController@store')->name('store');
  //  Route::get('/{tbl_name}/{article}/comment/{comment}', 'BbsCommentController@store')->name('store');//for test
    Route::delete('/{tbl_name}/{article}/comment/{comment}', 'BbsCommentController@destroy')->name('destroy');
    Route::put('/{tbl_name}/{article}/comment/{comment}', 'BbsCommentController@update')->name('update');

});
