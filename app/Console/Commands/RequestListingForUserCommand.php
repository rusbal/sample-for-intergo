<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Four13\AmazonMws\RequestReport;
use Illuminate\Support\Facades\Auth;

class RequestListingForUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:request-listing-user
                            {user : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request listing for specified user';

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
        $userId = $this->argument('user');
        $user = User::find($userId);

        if (! $user) {
            $this->info("--- User ID $userId does not exist.");
            return;
        }

        /**
         * requestListing() only executes if amazonMws settings exists.
         * If it does not exist, amazonMws is NullObject.
         */
        if ($user->amazonMws->isNull()) {
            $this->info("--- User: [{$user->id}] {$user->name} has no Amazon MWS settings yet.");
            return;
        }

        $this->requestReports($user,
            ['_GET_MERCHANT_LISTINGS_ALL_DATA_']
        );

        $this->info('Job: Start report requests executed.');
    }

    /**
     * Private
     */

    private function requestReports($user, $reports)
    {
        foreach ($reports as $reportType) {
            Auth::loginUsingId($user->id);

            if (RequestReport::queue($user->id, $reportType, true)) {
                $this->info("*** User: [{$user->id}] {$user->name} {$reportType} requested.");
            } else {
                $this->info("--- User: [{$user->id}] {$user->name} {$reportType} request already exists.");
            }

            Auth::logout();
        }
    }
}
