<?php

namespace App\Jobs;

use App\AmazonRequestQueue;
use Illuminate\Bus\Queueable;
use Four13\AmazonMws\RequestReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAmazonQueue implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $queue = AmazonRequestQueue::where('request_id', '!=', '')->get();

        foreach ($queue as $item) {
            Auth::loginUsingId($item->store_name);
            RequestReport::process($item);
            Auth::logout();
        }
    }
}
