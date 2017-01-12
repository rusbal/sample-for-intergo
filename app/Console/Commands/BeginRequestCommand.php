<?php

namespace App\Console\Commands;

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
    protected $description = 'Start report requests to Amazon';

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
        $queue = AmazonRequestQueue::where('request_id', '')->get();

        foreach ($queue as $item) {
            Auth::loginUsingId($item->store_name);
            RequestReport::initiate($item);
            Auth::logout();
        }

        $this->info('Job: Start report requests executed.');
    }
}
