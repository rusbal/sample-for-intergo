<?php

namespace Four13\Stripe;

use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;

class ChargeReport
{
    protected $stripeId;

    public function __construct($stripeId)
    {
        $this->stripeId = $stripeId;
    }

    public function getCharges()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $report = Charge::all([
            'customer' => $this->stripeId,
        ]);

        return array_map(
            function($transaction) {
                return $this->formatCharge($transaction);
            }, $report->data
        );
    }

    public function getNextPayment()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $output['date'] = null;
        $output['dateMdy'] = null;
        $output['amount'] = null;

        if ($customer = Customer::retrieve($this->stripeId)) {
            if ($customer->subscriptions->data) {
                $subscription = $customer->subscriptions->data[0];
                $output['date'] = date('Y-m-d', $subscription->current_period_end);
                $output['dateMdy'] = date('M d, Y', $subscription->current_period_end);
                $output['amount'] = $this->toAmount($subscription->plan->amount);
            }
        }

        return $output;
    }

    /**
     * Private
     */

    private function formatCharge($transaction)
    {
        $netAmount = 0;

        if ($transaction->paid) {
            $netAmount = $transaction->amount;
        }
        if ($transaction->refunded) {
            $netAmount = $netAmount - $transaction->amount_refunded;
        }

        return [
            'status' => $transaction->status,
            'date' => date('Y-m-d', $transaction->created),
            'dateMdy' => date('M d, Y', $transaction->created),
            'datetime' => date('Y-m-d H:i:s', $transaction->created),
            'currency' => $transaction->currency,
            'amount' => $this->toAmount($transaction->amount),
            'amount_refunded' => $this->toAmount($transaction->amount_refunded),
            'net_amount' => $this->toAmount($netAmount),
        ];
    }

    private function toAmount($amount)
    {
        return $amount / 100;
    }
}