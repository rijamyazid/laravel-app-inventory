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

//Admin Route
Route::get('/{role}', 'AdminController@index');
Route::get('/{role}/folder/{url_path?}/create', 'AdminController@createFolder');
Route::post('/{role}/folder/{url_path?}/creating', 'AdminController@createFolderProcess');
Route::get('/BidangAdpin', 'BidangAdpinController@index');
Route::get('/BidangKbkr', 'BidangKbkrController@index');
Route::get('/BidangKspk', 'BidangKspkController@index');
Route::get('/BidangDalduk', 'BidangDaldukController@index');
Route::get('/BidangLatbang', 'BidangLatbangController@index');



Route::get('/{bidang}/folder/{any?}', function($bidang, $any = ''){
    echo $bidang;
    echo $any;
})->where(['bidang' =>'BidangSekretariat|BidangAdpin|BidangKbkr|BidangKspk|BidangDalduk|BidangLatbang', 
            'any' =>'.*']);