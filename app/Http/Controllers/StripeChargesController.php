<?php

namespace App\Http\Controllers;

use Four13\Stripe\ChargeReport;
use Illuminate\Support\Facades\Auth;

class StripeChargesController extends Controller
{
    protected $user;

    protected $stripeId;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->stripeId = $this->user->stripe_id;
            return $next($request);
        });
    }

    public function index()
    {
        $chargeReport = new ChargeReport($this->stripeId);

        return [
            'charges' => $chargeReport->getCharges(),
            'next_payment' => $chargeReport->getNextPayment(),
        ];
    }
}
