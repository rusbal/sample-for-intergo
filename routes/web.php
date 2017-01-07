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

Route::get('captcha-form-validation', [
    'as' => 'google.get-recaptcha-validation-form',
    'uses' => 'FileController@getCaptchaForm']) ;

Route::post('captcha-form-validation', [
    'as' => 'google.post-recaptcha-validation',
    'uses' => 'FileController@postCaptchaForm']) ;

Route::resource('/admin/settings', 'UserSettingsController');
