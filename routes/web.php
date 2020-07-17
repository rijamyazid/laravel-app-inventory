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

Route::get('/', function () {
    return view('auth.login');
});

//Login Route
Route::post('/login', 'LoginController@auth');

//Logout Route
Route::get('/logout', 'AdminController@logout');

//Admin Route
Route::get('/{role_prefix}', 'AdminController@index');
Route::get('/{role_prefix}/folder/{url_path?}', 'AdminController@view')->where('url_path', '.*');
Route::get('/{role_prefix}/create/folder/{url_path?}', 'AdminController@createFolder')->where('url_path', '.*');
Route::post('/{role_prefix}/creating/folder/{url_path?}', 'AdminController@createFolderProcess')->where('url_path', '.*');
Route::get('/{role_prefix}/edit/folder/{folderID}', 'AdminController@edit');
Route::post('/{role_prefix}/update/folder/{folderID}', 'AdminController@update');
Route::get('/{role_prefix}/delete/folder/{folderID}', 'AdminController@deleteFolder');

//Files Route
Route::get('/{role_prefix}/create/files/{url_path?}', 'FilesController@create')->where('url_path', '.*');
Route::post('/{role_prefix}/store/files/{url_path?}', 'FilesController@store')->where('url_path', '.*');
Route::get('/{role_prefix}/destroy/file/{uuid}', 'FilesController@destroy')->where('url_path', '.*');
Route::get('/{role_prefix}/download/file/{uuid}', 'FilesController@download')->where('url_path', '.*');

Route::get('/{bidang}/folder/{any?}', function($bidang, $any = ''){
    echo $bidang;
    echo $any;
})->where(['bidang' =>'BidangSekretariat|BidangAdpin|BidangKbkr|BidangKspk|BidangDalduk|BidangLatbang', 
            'any' =>'.*']);