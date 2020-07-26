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
Route::get('/guest_login', 'LoginController@guest');
Route::get('/{bidang}/dashboard', 'AdminController@index');

//Logout Route
Route::get('/logout', 'AdminController@logout');

//Super Admin Route
Route::get('/{role_prefix}/view/admin', 'AdminController@viewAdmin');
Route::get('/{role_prefix}/create/admin', 'AdminController@createAdmin');
Route::post('/{role_prefix}/store/admin', 'AdminController@storeAdmin');
Route::get('/{role_prefix}/edit/admin/{username}', 'AdminController@editAdmin');
Route::post('/{role_prefix}/update/admin/{username}', 'AdminController@updateAdmin');
Route::get('/{role_prefix}/delete/admin/{username}', 'AdminController@deleteAdmin');

//Folders Route
Route::get('/{role_prefix}/create/folder/{url_path?}', 'FoldersController@create')->where('url_path', '.*');
Route::post('/{role_prefix}/create/bidang-baru', 'FoldersController@createNewBidang');
Route::post('/{role_prefix}/creating/folder/{url_path?}', 'FoldersController@store')->where('url_path', '.*');
Route::get('/{role_prefix}/folder/{url_path?}', 'FoldersController@view')->where('url_path', '.*');
Route::get('/{role_prefix}/edit/folder/{folderID}', 'FoldersController@edit');
Route::post('/{role_prefix}/update/folder/{folderID}', 'FoldersController@update');
Route::get('/{role_prefix}/delete/folder/{folderID}', 'FoldersController@delete');

//Files Route
Route::get('/{role_prefix}/create/files/{url_path?}', 'FilesController@create')->where('url_path', '.*');
Route::post('/{role_prefix}/store/files/{url_path?}', 'FilesController@store')->where('url_path', '.*');
Route::get('/{role_prefix}/destroy/file/{uuid}', 'FilesController@destroy')->where('url_path', '.*');
Route::get('/{role_prefix}/download/file/{uuid}', 'FilesController@download')->where('url_path', '.*');
Route::get('/{role_prefix}/search', 'FoldersController@search');