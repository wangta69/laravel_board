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
//namespace App\Http\Controllers\Bbs;
Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {
    
    Route::get('admin', 'AdminController@index')->name('admin');
    Route::get('admin/create', 'AdminController@create')->name('admin.bbs.create');
    Route::post('admin/create', 'AdminController@store')->name('admin.bbs.store');
    Route::delete('admin/{tbl_id}/delete', 'AdminController@destroy')->name('admin.bbs.destroy');
   // Route::get('{tbl_id}/board', 'BoardController@index')->name('board');
   
    Route::post('/plugins/{plugins}/file-upload', 'PluginsController@fileUpload')->name('plugin.fileupload');
    Route::get('/plugins/{plugins}/{action}', 'PluginsController@index')->name('plugin.index');
    Route::get('/plugins/{plugins}', 'PluginsController@index')->name('plugin.index');
    
    Route::get('/{file_id}/download', 'BoardController@download')->name('download');
    
    Route::get('/{tbl_name}/create', 'BoardController@createForm')->name('create');
    Route::post('/{tbl_name}/store', 'BoardController@store')->name('store');
    Route::put('/{tbl_name}/{article_id}/store', 'BoardController@update')->name('update');
    Route::get('/{tbl_name}/{article_id}/edit', 'BoardController@editForm')->name('edit');
    Route::delete('/{tbl_name}/{article_id}/destroy', 'BoardController@destroy')->name('destroy');
    Route::get('/{tbl_name}/{article_id}/show', 'BoardController@show')->name('show');
    Route::get('/{tbl_name}', 'BoardController@index')->name('index');
    
});