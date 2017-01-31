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

// Enable showing of SQL
DB::enableQueryLog();

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail');

Route::get('/home', 'HomeController@index');

Route::resource('/my/dashboard', 'DashboardController');
Route::resource('/my/settings', 'UserSettingsController');
Route::resource('/my/subscription', 'SubscriptionController');
Route::get('/my/invoices', 'MyInvoiceController@index');

/**
 * Reports
 */
Route::get('/my/reports/daily-revenue', 'ReportController@dailyRevenue');
Route::get('/my/reports/revenue/{startDate}/{endDate}', 'ReportController@customDateRevenue');

/**
 * Ajax
 */
Route::patch('/ajax/aml/monitor/{listing}', 'AmlMonitorController@update');
Route::get('/ajax/aml/listing', 'AmazonListingController@index');
Route::get('/ajax/stripe/plans', 'StripeController@getPlans');
Route::get('/ajax/stripe/invoices', 'StripeChargesController@index');

