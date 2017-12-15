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

Route::group(['prefix' => 'bbs', 'as' => 'bbs.', 'namespace' => 'Bbs'], function () {
    Route::get('admin', 'AdminController@index')->name('admin');
    Route::get('admin/create', 'AdminController@create')->name('admin.bbs.create');
    Route::post('admin/create', 'AdminController@store')->name('admin.bbs.store');
    Route::delete('admin/{tbl_id}/delete', 'AdminController@destroy')->name('admin.bbs.destroy');
    Route::get('{tbl_id}/board', 'BoardController@index')->name('board');
});
