<?php

namespace App\Console\Commands;

use App\AmazonMws;
use App\AmazonRequestQueue;
use Illuminate\Console\Command;
use Four13\AmazonMws\RequestReport;
use Illuminate\Support\Facades\Auth;

class PokeAmazonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:poke-amazon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poke Amazon MWS on requests.';

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
        $queue = AmazonRequestQueue::where('request_id', '!=', '')->get();

        foreach ($queue as $item) {
            Auth::loginUsingId($item->store_name);

            $dataHandler = AmazonMws::getDataHandler($item->type);

            if (! $dataHandler) {
                $this->info("--- No data handler for request type: $item->type");
                continue;
            }

            $result = RequestReport::process($dataHandler, $item);
            if ($result) {
                $this->info('*** Success: Poked and got data from Amazon.');
            } else {
                $this->info('--- Poked but no data from Amazon yet.');
            }

            Auth::logout();
        }

        $this->info('Job: Poke Amazon requests executed.');
    }
}
