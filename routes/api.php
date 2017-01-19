<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


/*
|--------------------------------------------------------------------------
| Special API Routes
|--------------------------------------------------------------------------
|
| Here aml/{param} is mapped to Api\AmlController@index where execution is
| delegated to the value of param.
|
| Callable methods:
|   Routes              Methods
|   ===============     =========
|   aml/listing/        @listing
|   ...
|   including all public methods inside AmlController
|
*/
Route::get('aml/method/{method}', 'Api\AmlController@method');
Route::resource('aml', 'Api\AmlController');
