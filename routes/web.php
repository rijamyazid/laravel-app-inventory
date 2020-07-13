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
Route::get('/bidang-sekretariat', 'BidangSekretariatController@index');
Route::get('/bidang-adpin', 'BidangAdpinController@index');
Route::get('/bidang-kbkr', 'BidangKbkrController@index');
Route::get('/bidang-kspk', 'BidangKspkController@index');
Route::get('/bidang-dalduk', 'BidangDaldukController@index');
Route::get('/bidang-latbang', 'BidangLatbangController@index');

Route::get('admin/{any?}', function($any = ''){
    dd($any);
})->where('any', '.*');