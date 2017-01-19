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
    return view('welcome');
});

Auth::routes();
Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail');

Route::get('/home', 'HomeController@index');

Route::resource('/my/settings', 'UserSettingsController');
Route::resource('/my/dashboard', 'DashboardController');

Route::resource('/my/user', 'RayUserController');
Route::resource('/ray/listing', 'Api\ListingController');
