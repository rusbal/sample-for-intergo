<?php

namespace App\Http\Controllers;

use App\Http\Traits\Ajax;
use Illuminate\Http\Request;
use App\AmazonMerchantListing;
use Illuminate\Support\Facades\Auth;

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

        return $this->updateWillMonitor($listing, $willMonitor);
    }

    /**
     * Private
     */

    private function updateWillMonitor($listing, $willMonitor)
    {
        if ($willMonitor === 1) {
            $stats = Auth::user()->planStats();
            if ($stats['isUsedUp']) {
                return $this->failure("Plan allocation already used up!  [" . strtoupper($stats['plan']) . ": {$stats['monitorCount']}]");
            }
        }

        $listing->will_monitor = $willMonitor;
        $listing->save();

        if (true) {
            return $this->success("will_monitor set to $willMonitor");
        } else {
            return $this->failure("will_monitor not set");
        }
    }
}
