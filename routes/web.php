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

//Route::post('/log', 'LogController@log')->name('log');
//Route::get('/pdf/test', 'DocumentController@downloadPdf');
//Route::get('/pdf/test2', 'DocumentController@pdf');
//Route::get('/api/admin/slip/csvlist',  'SlipController@csvlist');

// Authentication Routes...
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('show.login');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/agree', 'UserController@agree')->name('agree');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'can:admin-higher']], function () {

  // USER
  Route::post('/api/admin/user',          'UserController@index')->name('admin.user');
  Route::post('/api/admin/user/download', 'UserController@download')->name('admin.user.download');
  Route::post('/api/admin/user/upload',   'UserController@upload')->name('admin.user.upload');

  // SLIP 
  Route::post('/api/admin/slip/csvlist',  'SlipController@csvlist')->name('admin.slip.csvlist');
  Route::post('/api/admin/slip/sliplist', 'SlipController@sliplist')->name('admin.slip.sliplist');
  Route::post('/api/admin/slip/upload',   'SlipController@upload')->name('admin.slip.upload');

  // PDF
  Route::post('/api/admin/pdf/slip',      'PDFController@slip')->name('admin.pdf.slip');

  // ActionLOG
  Route::post('/api/admin/actlog',        'ActlogController@index')->name('admin.actlog');

});

Route::get('/{any}', function () {
    return view('home');
})->middleware('auth')->where('any', '.*');
