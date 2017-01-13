<?php

namespace App\Console\Commands;

use App\User;
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
    protected $description = 'Start report requests to Amazon for all users';

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
            if ($user->amazonRequestHistory->count() > 0) {
                $this->info("--- User: [{$user->id}] {$user->name} did not requested because history already exists.");
                continue;
            }

            /**
             * requestListing() only executes if amazonMws settings exists.
             * If it does not exist, amazonMws is NullObject.
             */
            $mws = $user->amazonMws;

            if ($mws->isNull()) {
                $this->info("--- User: [{$user->id}] {$user->name} has no Amazon MWS settings yet.");
                continue;
            }

            Auth::loginUsingId($user->id);
            if ($mws->requestListing()) {
                $this->info("*** User: [{$user->id}] {$user->name} listing requested.");
            } else {
                $this->info("--- User: [{$user->id}] {$user->name} request already exists.");
            }
            Auth::logout();
        }

        $this->info('Job: Start report requests executed.');
    }

    /**
     * Seems this is no longer needed.
     * Its purpose is to initiate a request which should be automatically initiated anyway
     * unless the amazon_mws_auth is not passed which was the problem this code solves.
     * Now that we pass amazon_mws_auth, there is no longer need for this.
     */
    private function initiateOnly()
    {
        $queue = AmazonRequestQueue::where('request_id', '')->get();

        foreach ($queue as $item) {
            Auth::loginUsingId($item->store_name);

            $result = RequestReport::initiate($item);

            if ($result) {
                $this->info("*** Successfully started request for: {$item->store_name}");
            } else {
                $this->info("--- Failed to started request for: {$item->store_name}");
            }

            Auth::logout();
        }

        $this->info('Job: Start report requests executed.');
    }
}
