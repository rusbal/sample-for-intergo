<?php

namespace Four13\Stripe;


use Stripe\Stripe;
use App\ObjectTrait;

class Plan
{
    use ObjectTrait;

    public function getPlans()
    {
        $this->prepare();
        $plans = \Stripe\Plan::all();

        $data = array_reduce($plans->data,
            function ($output, $plan) {
                $output[$plan->id] = [
                    'amount' => $plan->amount / 100,
                    'currency' => $plan->currency,
                    'interval' => $plan->interval,
                    'name' => $plan->name,
                    'trial_period_days' => $plan->trial_period_days,
                    'description' => $plan->metadata->description,
                ];
                return $output;
            }
        );

        return $this->uasortByKey($data, 'amount');
    }

    /**
     * Private
     */

    private function uasortByKey($arr, $key)
    {
        uasort($arr, function($a, $b) use ($key){
            $aVal = $a[$key];
            $bVal = $b[$key];

            if ($aVal == $bVal) {
                return 0;
            }
            return ($aVal < $bVal) ? -1 : 1;
        });

        return $arr;
    }

    private function prepare()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
}