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

// Authentication Routes...
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'can:admin-higher']], function () {
  Route::post('/api/admin/user/', 'UserController@index')->name('admin/user');
  Route::post('/api/admin/user/download', 'UserController@download');
});


Route::get('/{any}', function () {
    Route::auth();
    return view('home');
})->where('any', '.*');

