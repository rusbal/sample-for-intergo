<?php

namespace App\Console\Commands;

use App\AmazonMws;
use App\AmazonRequestQueue;
use Four13\AmazonMws\RequestReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

/**
 * Created: 1 March 2017
 *
 * Works the same as ProcessQueueCommand
 *   except that this does not delegate to Jobs/ProcessAmazonQueue.php,
 *   instead it runs it here immediately.
 */
class ProcessQueueImmediateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:process-queue-immediate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the Amazon MWS request queue here and now without pushing to queue.';

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
