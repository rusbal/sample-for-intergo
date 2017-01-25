<?php

use App\User;

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

/**
 * Stripe
 */

Route::get('/stripe/token', function(){
    $csrf = csrf_field();
    echo <<<STR
<form action="/stripe/subscribe" method="POST">
  $csrf
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_CWSEzdTtKyYISl37MjOWsEsf"
    data-amount="1000"
    data-name="SKUBright"
    data-description="Monthly Subscription"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto">
  </script>
</form>
STR;

});

Route::post('/stripe/subscribe', function(\Illuminate\Http\Request $request){

    $user = User::find(1);
    $creditCardToken = $request->get('stripeToken');

    $user->newSubscription('main', 'monthly')->create($creditCardToken, [
        'description' => $user->name,
        'email' => $user->email,
    ]);

    return 'User is ' . ($user->subscribed('main') ? ' ' : 'not ') . 'subscribed';
});

Route::get('/stripe/status', function(){
    $user = User::find(1);

    $subs = $user->subscription('main');
    $subs->cancelNow();

    return 'User is ' . ($user->subscribed('main') ? ' ' : 'not ') . 'subscribed';
});

