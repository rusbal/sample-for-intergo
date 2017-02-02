<?php

namespace App\Http\Controllers;

use App\Http\Traits\Ajax;
use Illuminate\Http\Request;
use App\AmazonMerchantListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AmlMonitorController extends Controller
{
    use Ajax;

    /**
     * Ajax: Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  AmazonMerchantListing  $listing
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, AmazonMerchantListing $listing)
    {
        $willMonitor = $request->get('will_monitor');

        if (is_null($willMonitor)) {
            throw new \Exception('Invalid call to AmlMonitorController@update.  Missing will_monitor');
        }
        $minimumAdvertizedPrice = $request->get('minimum_advertized_price');
        $maximumOfferQuantity   = $request->get('maximum_offer_quantity');

        if ($willMonitor === 1) {
            if (! $minimumAdvertizedPrice && ! $maximumOfferQuantity) {
                throw new \Exception('Invalid call to AmlMonitorController@update.  Both fields empty: minimum_advertized_price, maximum_offer_quantity');
            }
        }

        return $this->updateWillMonitor($listing, $willMonitor, $minimumAdvertizedPrice, $maximumOfferQuantity);
    }

    // PRIVATE

    /**
     * @param AmazonMerchantListing $listing
     * @param $willMonitor
     * @param $minimumAdvertizedPrice
     * @param $maximumOfferQuantity
     * @return array
     */
    private function updateWillMonitor($listing, $willMonitor, $minimumAdvertizedPrice, $maximumOfferQuantity)
    {
        if ($willMonitor === 1) {
            $stats = Auth::user()->planStats();

            if ($stats->isUsedUp) {
                return $this->failure("Plan allocation already used up!  [" . strtoupper($stats->plan) . ": {$stats->monitorCount}]");
            }

            $listing->minimum_advertized_price = $minimumAdvertizedPrice;
            $listing->maximum_offer_quantity   = $maximumOfferQuantity;
        }

        $listing->will_monitor = $willMonitor;
        $listing->save();

        /**
         * NOTE: Using DB here because we do not want cached count.
         */
        $newCount = DB::select(
            'SELECT COUNT(*) AS count FROM amazon_merchant_listings WHERE user_id = ? AND will_monitor = 1',
            [Auth::id()]
        )[0]->count;

        return $this->success(
            "will_monitor set to $willMonitor",
            ['monitoredListingCount' => $newCount]
        );
    }
}
