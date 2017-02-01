<?php

namespace App\Console\Commands;

use App\User;
use App\AmazonMws;
use App\AmazonRequestQueue;
use Illuminate\Console\Command;
use Four13\AmazonMws\RequestReport;
use Illuminate\Support\Facades\Auth;

class BeginRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:begin-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start report requests to Amazon for all users including quantity update';

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
        $users = User::all();

        foreach ($users as $user) {

            /**
             * requestListing() only executes if amazonMws settings exists.
             * If it does not exist, amazonMws is NullObject.
             */
            if ($user->amazonMws->isNull()) {
                $this->info("--- User: [{$user->id}] {$user->name} has no Amazon MWS settings yet.");
                continue;
            }

            $this->requestReports($user,
                $this->requestableReports($user)
            );
        }

        $this->info('Job: Start report requests executed.');
    }

    /**
     * Private
     */

    private function requestReports($user, $reports)
    {
        foreach ($reports as $reportType) {
            Auth::loginUsingId($user->id);

            if (RequestReport::queue($user->id, $reportType)) {
                $this->info("*** User: [{$user->id}] {$user->name} {$reportType} requested.");
            } else {
                $this->info("--- User: [{$user->id}] {$user->name} {$reportType} request already exists.");
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
            $this->info("--- User: [{$user->id}] {$user->name} {$history->type} history already exists");
            $onHistory[] = $history->type;
        }

        /**
         * Merchant listing data must exist before calling _GET_AFN_INVENTORY_DATA_.
         * Thus, we remove _GET_AFN_INVENTORY_DATA_ if _GET_MERCHANT_LISTINGS_ALL_DATA_
         * is not in history.
         */
        if (! in_array('_GET_MERCHANT_LISTINGS_ALL_DATA_', $onHistory)) {
            $reports = array_diff($reports, ['_GET_AFN_INVENTORY_DATA_']);
        }

        // Remove from reports to request
        return array_diff($reports, $onHistory);
    }
}
