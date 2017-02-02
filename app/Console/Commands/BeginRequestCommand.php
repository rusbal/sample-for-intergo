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
        $onHistory = $this->getRequestHistoryFor($user);

        if (empty($onHistory)) {
            return ['_GET_MERCHANT_LISTINGS_ALL_DATA_'];
        }

        return AmazonMws::REPORT_REQUEST_TYPES;
    }

    /**
     * Returns distinct request history for user
     * Example output:
     *   [
     *     "_GET_MERCHANT_LISTINGS_ALL_DATA_",
     *     "_GET_AFN_INVENTORY_DATA_",
     *   ]
     *
     * @param User $user
     * @return array
     */
    private function getRequestHistoryFor($user)
    {
        return array_map(
            function($history){ return $history['type']; },
            $user->amazonRequestHistory()->distinct()->select('type')->get()->toArray()
        );
    }
}
