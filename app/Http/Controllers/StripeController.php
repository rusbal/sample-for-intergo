<?php

namespace App\Http\Controllers;

use Four13\Stripe\Plan;

class StripeController extends Controller
{
    public function getPlans()
    {
        return (new Plan)->getPlans();
    }
}
