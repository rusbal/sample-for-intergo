<?php

namespace App\Jobs;

use App\AmazonMws;
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
        $queue = AmazonRequestQueue::all();

        foreach ($queue as $item) {
            Auth::loginUsingId($item->store_name);

            $dataHandler = AmazonMws::getDataHandler($item->type, $item->class);

            if (! $dataHandler) {
                $this->info("--- No data handler for request type: $item->type");
                continue;
            }

            if ($item->request_id == '') {
                RequestReport::initiate($item);
            } else {
                RequestReport::process($dataHandler, $item);
            }

            Auth::logout();
        }
    }
}
