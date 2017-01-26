<?php

namespace App\Http\Controllers;

use Four13\Plans\Plan;
use App\Http\Traits\Ajax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    use Ajax;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->isSubscribed()) {
            return redirect()->action('SubscriptionController@show', ['id' => $this->user->id]);
        }

        return view('my.plans');
    }

    /**
     * Show the form for creating a new resource.
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
        if ($request->ajax()) {
            return $this->storeSessionRedirect($request);

        } else {
            /**
             * Step 2 New
             */
            $this->successStripeCallback($request);
            return redirect()->action('SubscriptionController@show', ['id' => $this->user->id]);
        }
    }

    /**
     * Private
     */

    /**
     * New Credit card registration to Stripe callback
     *
     * @param $request
     */
    private function successStripeCallback($request)
    {
        $plan = session('stripe_plan');
        $token = $request->get('stripeToken');

        $this->subscriptionNew($plan, $token);

        $request->session()->forget('stripe_plan');
        $request->session()->forget('stripe_amount');

        session()->flash('message', 'You have successfully subscribed with plan: ' . strtoupper($plan));
    }

    private function storeSessionRedirect($request)
    {
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

    private function subscriptionNew($plan, $token)
    {
        $this->user->newSubscription('main', $plan)->create($token, [
            'description' => $this->user->name,
            'email' => $this->user->email,
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
     * Ajax: Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        if (! $request->ajax()) {
            throw new \Exception('Invalid non-AJAX call to SubscriptionController@update.');
        }

        $plan = $request->get('plan');

        if ($plan) {
            return $this->updateSubscription($plan);
        }

        throw new \Exception('Error: Invalid call to SubscriptionController@update.  Missing plan.');
    }

    /**
     * Private
     */

    private function updateSubscription($plan)
    {
        $stats = $this->user->planStats();

        if (Plan::canSwapPlan($stats, $plan)) {

            $this->user->subscription('main')->swap($plan);

            return $this->success("Subscription updated to '$plan'.", [
                'userPlanStats' => $this->user->planStats()
            ]);

        } else {
            return $this->failedSwapAllocation($stats, $plan);
        }
    }

    private function failedSwapAllocation($stats, $plan)
    {
        $newAllocation = Plan::getAllocationFor($plan);
        $difference = $stats->monitorCount - $newAllocation;

        return $this->failure("Cannot switch to plan: " . strtoupper($plan) . ".  Please unselect $difference items from your monitored items first then try again.");
    }
}
