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
Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'App\Http\Controllers\Bbs', 'middleware' => ['web']], function () {
    
//Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'Bbs', 'middleware' => ['web']], function () {
    Route::get('admin', 'AdminController@index')->name('admin');
    Route::get('admin/create', 'AdminController@createForm')->name('admin.create');
    Route::get('admin/{tbl_id}/create', 'AdminController@createForm')->name('admin.show');
    Route::post('admin/create', 'AdminController@store')->name('admin.store');
    Route::put('admin/{tbl_id}/create', 'AdminController@update')->name('admin.store');
    Route::delete('admin/{tbl_id}/delete', 'AdminController@destroy')->name('admin.destroy');
   // Route::get('{tbl_id}/board', 'BbsController@index')->name('board');
   
    Route::post('/plugins/{plugins}/file-upload', 'PluginsController@fileUpload')->name('plugin.fileupload');
    Route::get('/plugins/{plugins}/{action}', 'PluginsController@index')->name('plugin.index');
    Route::get('/plugins/{plugins}', 'PluginsController@index')->name('plugin.index');
    
    Route::get('/{file_id}/download', 'BbsController@download')->name('download');
    
    Route::get('/{tbl_name}/create', 'BbsController@createForm')->name('create');
    Route::post('/{tbl_name}/store', 'BbsController@store')->name('store');
    Route::put('/{tbl_name}/{article_id}/store', 'BbsController@update')->name('update');
    Route::get('/{tbl_name}/{article_id}/edit', 'BbsController@editForm')->name('edit');
    Route::delete('/{tbl_name}/{article_id}/destroy', 'BbsController@destroy')->name('destroy');
    Route::get('/{tbl_name}/{article_id}/show', 'BbsController@show')->name('show');
    Route::get('/{tbl_name}', 'BbsController@index')->name('index');
    
});