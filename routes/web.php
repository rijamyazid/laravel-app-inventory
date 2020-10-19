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
Route::get('/{bidangPrefix}/dashboard', 'AdminController@index');

//Logout Route
Route::get('/logout', 'AdminController@logout');

//Super Admin Route
Route::get('/{bidangPrefix}/view/admin', 'AdminController@viewAdmin');
Route::get('/{bidangPrefix}/create/admin', 'AdminController@createAdmin');
Route::post('/{bidangPrefix}/store/admin', 'AdminController@storeAdmin');
Route::get('/{bidangPrefix}/edit/admin/{username}', 'AdminController@editAdmin');
Route::post('/{bidangPrefix}/update/admin/{username}', 'AdminController@updateAdmin');
Route::get('/{bidangPrefix}/delete/admin/{username}', 'AdminController@deleteAdmin');

//Folders Route
Route::get('/{bidangPrefix}/create/folder/{url_path?}', 'FoldersController@create')->where('url_path', '.*');
Route::post('/{bidangPrefix}/create/bidang-baru', 'FoldersController@createNewBidang');
Route::post('/{bidangPrefix}/creating/folder/{url_path?}', 'FoldersController@store')->where('url_path', '.*');
Route::get('/{bidangPrefix}/folder/{url_path?}', 'FoldersController@view')->where('url_path', '.*');
Route::get('/{bidangPrefix}/edit/folder/{folderID}', 'FoldersController@edit');
Route::post('/{bidangPrefix}/update/folder/{folderID}', 'FoldersController@update');
Route::get('/{bidangPrefix}/move/folder/{folderID}', 'FoldersController@move');
Route::get('/{bidangPrefix}/moving/folder/{url_path?}', 'FoldersController@moving')->where('url_path', '.*');
Route::get('/{bidangPrefix}/delete/folder/{folderID}', 'FoldersController@delete');

//Files Route
Route::get('/{bidangPrefix}/create/files/{url_path?}', 'FilesController@create')->where('url_path', '.*');
Route::post('/{bidangPrefix}/store/files/{url_path?}', 'FilesController@store')->where('url_path', '.*');
Route::get('/{bidangPrefix}/edit/file/{uuid}', 'FilesController@edit');
Route::post('/{bidangPrefix}/update/file/{uuid}', 'FilesController@update');
Route::get('/{bidangPrefix}/destroy/file/{uuid}', 'FilesController@destroy')->where('url_path', '.*');
Route::get('/{bidangPrefix}/download/file/{uuid}', 'FilesController@download')->where('url_path', '.*');
Route::get('/{bidangPrefix}/move/file/{uuid}', 'FilesController@move');
Route::get('/{bidangPrefix}/moving/file/{url_path?}', 'FilesController@moving')->where('url_path', '.*');
Route::get('/{bidangPrefix}/search', 'FoldersController@search');