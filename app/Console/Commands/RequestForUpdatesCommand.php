<?php

namespace App\Console\Commands;

use App\AmazonMws;
use Illuminate\Console\Command;
use Four13\AmazonMws\RequestReport;
use Illuminate\Support\Facades\Auth;

class RequestForUpdatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:update-data-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request for updates on Merchant listing from Amazon';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mws = AmazonMws::all();

        foreach ($mws as $setting) {
            $this->requestReports($setting->user,
                $this->requestableReports($setting->user)
            );
        }

        $this->info('Job: Data update requested.');
    }

    /**
     * Private
     */

    private function requestReports($user, $reports)
    {
        foreach ($reports as $reportType) {
            Auth::loginUsingId($user->id);

            if (RequestReport::queue($user->id, $reportType, true)) {
                $this->info("*** User: [{$user->id}] {$user->name} {$reportType} data-update requested.");
            } else {
                $this->info("--- User: [{$user->id}] {$user->name} {$reportType} data-update request already exists.");
            }

            Auth::logout();
        }
    }

    /**
     * Returns an array of requestable reports.
     * These are report types that do not exist on history yet.
     *
     * @param $user
     * @return array
     */
    private function requestableReports($user)
    {
        $reports = AmazonMws::REPORT_REQUEST_TYPES;
        $onHistory = [];

        foreach ($user->amazonRequestHistory as $history) {
            $onHistory[] = $history->type;
        }

        /**
         * Merchant listing data must exist before calling _GET_AFN_INVENTORY_DATA_.
         * Thus, we remove _GET_AFN_INVENTORY_DATA_ if _GET_MERCHANT_LISTINGS_DATA_
         * is not in history.
         */
        if (! in_array('_GET_MERCHANT_LISTINGS_DATA_', $onHistory)) {
            $reports = array_diff($reports, ['_GET_AFN_INVENTORY_DATA_']);
        }

        return $reports;
    }
}
