<?php

namespace App\Http\Controllers;

use App\Http\Traits\Ajax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    use Ajax;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isSubscribed()) {
            return redirect()->action('SubscriptionController@show', ['id' => Auth::id()]);
        }

        return view('my.plans');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plan = session('stripe_plan');
        $amount = session('stripe_amount');

        if ($plan && ! is_null($amount)) {
            /**
             * Stripe Credit Card entry and payment
             */
            return view('my.stripe', compact('plan', 'amount'));
        }
        dd($plan, $amount);

        throw new \Exception('Invalid call to SubscriptionController@create.  missing plan, amount.');
    }

    /**
     * Store a newly created resource in storage via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            return $this->storeAjax($request, $user);
        } else {
            /**
             * Step 2 (New, Update)
             */
            $this->successStripeCallback($request, $user);
            return redirect()->action('SubscriptionController@show', ['id' => $user->id]);
        }
    }

    /**
     * Private
     */

    private function successStripeCallback($request, $user)
    {
        $plan = session('stripe_plan');
        $token = $request->get('stripeToken');

        $this->subscriptionNew($user, $plan, $token);

        $request->session()->forget('stripe_plan');
        $request->session()->forget('stripe_amount');

        session()->flash('message', 'You have successfully subscribed with plan: ' . strtoupper($plan));
    }

    private function storeAjax($request, $user)
    {
        if ($token = $request->get('stripeToken')) {

            throw new \Exception('It errored here.  Check this out...');

            $plan = $request->get('plan');

            $this->subscriptionNew($user, $plan, $token);
            return $this->success('successfully subscribed', ['redirect' => false]);

        } else {
            /**
             * STEP 1 (New)
             *
             * Save values to session then allow the front-end to redirect.
             */
            session(['stripe_plan' => $request->get('plan')]);
            session(['stripe_amount' => $request->get('amount')]);

            /**
             * Redirect from front-end to SubscriptionController@create
             * to show Stripe credit card entry form.
             */
            return $this->success('credit card entry', ['redirect' => true]);
        }
    }

    private function subscriptionNew($user, $plan, $token)
    {
        $user->newSubscription('main', $plan)->create($token, [
            'description' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('my.plans');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $plan = $request->get('plan');

            if ($plan) {
                $user = Auth::user();
                $user->subscription('main')->swap($plan);
                return $this->success();
            }

            throw new \Exception('Error: Invalid call to SubscriptionController@update.  Missing plan.');

        } else {
            throw new \Exception('Invalid non-AJAX call to SubscriptionController@update.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}