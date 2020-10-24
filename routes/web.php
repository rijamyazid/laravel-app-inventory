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
Route::get('/{bidangPrefix}/dashboard', 'UsersController@index');

//Logout Route
Route::get('/logout', 'UsersController@logout');

//User Route
Route::get('/{bidangPrefix}/view/user', 'UsersController@view');
Route::get('/{bidangPrefix}/create/user', 'UsersController@create');
Route::post('/{bidangPrefix}/store/user', 'UsersController@store');
Route::get('/{bidangPrefix}/edit/user/{username}', 'UsersController@edit');
Route::post('/{bidangPrefix}/update/user/{username}', 'UsersController@update');
Route::get('/{bidangPrefix}/delete/user/{username}', 'UsersController@delete');

//Folders Route
Route::get('/{bidangPrefix}/create/folder/{url_path?}', 'FoldersController@create')->where('url_path', '.*');
Route::post('/{bidangPrefix}/creating/folder/{url_path?}', 'FoldersController@store')->where('url_path', '.*');
Route::get('/{bidangPrefix}/folder/{url_path?}', 'FoldersController@view')->where('url_path', '.*');
Route::get('/{bidangPrefix}/edit/folder/{folderID}', 'FoldersController@edit');
Route::post('/{bidangPrefix}/update/folder/{folderID}', 'FoldersController@update');
Route::get('/{bidangPrefix}/move/folder/{folderID}', 'FoldersController@move');
Route::get('/{bidangPrefix}/moving/folder/{url_path?}', 'FoldersController@moving')->where('url_path', '.*');
Route::get('/{bidangPrefix}/movingCancel/folder/{url_path?}', 'FoldersController@moveCancel')->where('url_path', '.*');
Route::get('/{bidangPrefix}/delete/folder/{folderID}', 'FoldersController@delete');
Route::get('/{bidangPrefix}/search', 'FoldersController@search');

//Files Route
Route::get('/{bidangPrefix}/create/files/{url_path?}', 'FilesController@create')->where('url_path', '.*');
Route::post('/{bidangPrefix}/store/files/{url_path?}', 'FilesController@store')->where('url_path', '.*');
Route::get('/{bidangPrefix}/edit/file/{uuid}', 'FilesController@edit');
Route::post('/{bidangPrefix}/update/file/{uuid}', 'FilesController@update');
Route::get('/{bidangPrefix}/destroy/file/{uuid}', 'FilesController@destroy')->where('url_path', '.*');
Route::get('/{bidangPrefix}/download/file/{uuid}', 'FilesController@download')->where('url_path', '.*');
Route::get('/{bidangPrefix}/move/file/{uuid}', 'FilesController@move');
Route::get('/{bidangPrefix}/moving/file/{url_path?}', 'FilesController@moving')->where('url_path', '.*');
Route::get('/{bidangPrefix}/movingCancel/file/{url_path?}', 'FilesController@moveCancel')->where('url_path', '.*');

//Bin Route
Route::get('/{bidangPrefix}/bin', 'BinsController@view');
Route::get('/{bidangPrefix}/restore/bin/folder/{folderId}', 'BinsController@restoreFolder');
Route::get('/{bidangPrefix}/delete/bin/folder/{folderId}', 'BinsController@deleteFolder');
Route::get('/{bidangPrefix}/restore/bin/file/{fileUUID}', 'BinsController@restoreFile');
Route::get('/{bidangPrefix}/delete/bin/file/{fileUUID}', 'BinsController@deleteFile');

//Bidang Route
Route::get('/super_admin/view/bidang', 'BidangController@view');
Route::post('/super_admin/create/bidang', 'BidangController@create');
Route::get('/super_admin/edit/bidang/{bidangId}', 'BidangController@edit');
Route::post('/super_admin/update/bidang/{bidangId}', 'BidangController@update');
Route::get('/super_admin/delete/bidang/{bidangId}', 'BidangController@delete');