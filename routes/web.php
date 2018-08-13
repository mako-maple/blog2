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

Route::get('/', function () {
    return view('home');
})->middleware('auth');

//Auth::routes();

Route::get('/pdf/test', 'DocumentController@downloadPdf');

// Authentication Routes...
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'can:admin-higher']], function () {

  // USER
  Route::post('/api/admin/user/',         'UserController@index')->name('admin/user');
  Route::post('/api/admin/user/download', 'UserController@download');
  Route::post('/api/admin/user/upload',   'UserController@upload');

  // Slip 
  Route::post('/api/admin/slip/csvlist',  'SlipController@csvlist');
  Route::post('/api/admin/slip/sliplist', 'SlipController@sliplist');
  Route::post('/api/admin/slip/upload',   'SlipController@upload');

});


Route::get('/{any}', function () {
    Route::auth();
    return view('home');
})->where('any', '.*');

