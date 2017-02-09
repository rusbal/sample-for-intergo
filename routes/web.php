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

Route::get('/', 'HomeController@landing');

Auth::routes();

Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail');
Route::get('register/unverified', 'Auth\RegisterController@unverified')->name('unverifiedUser');
Route::post('register/resend', 'Auth\RegisterController@resendVerificationLink')->name('resendVerificationLink');

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

Route::get('/my/reports/offer-violations', 'OfferViolationReportController@daily');
Route::get('/my/reports/offer-violations/{startDate}/{endDate}', 'OfferViolationReportController@customDate');

Route::get('/my/reports/price-violations', 'PriceViolationReportController@daily');
Route::get('/my/reports/price-violations/{startDate}/{endDate}', 'PriceViolationReportController@customDate');

/**
 * Ajax
 */
Route::patch('/ajax/aml/monitor/{listing}', 'AmlMonitorController@update');
Route::get('/ajax/aml/listing', 'AmazonListingController@index');
Route::get('/ajax/stripe/plans', 'StripeController@getPlans');
Route::get('/ajax/stripe/invoices', 'StripeChargesController@index');

