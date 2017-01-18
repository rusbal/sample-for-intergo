<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\PokeAmazonCommand::class,
        Commands\QueueProcessorCommand::class,
        Commands\RequestForUpdatesCommand::class,

        Commands\BeginRequestCommand::class,
        Commands\PauseRequestsCommand::class,
        Commands\UnpauseRequestsCommand::class,
        Commands\DiscardRequestsCommand::class,

        Commands\ShowRequestsCommand::class,
        Commands\ShowInvalidSkuCommand::class,
        Commands\ShowInventoryFetchCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * begin-request checks the following:
         *
         * 1. Amazon MWS exists but no _GET_MERCHANT_LISTINGS_DATA_ request on Table: amazon_request_histories
         * 2. _GET_MERCHANT_LISTINGS_DATA_ exists but no _GET_AFN_INVENTORY_DATA_ on Table: amazon_request_histories
         *
         * If so, it initiates a request and writes it to Table: amazon_request_queues with a class=report.
         * It does not insert a new request if there is an existing similar request on queue.
         */
        $schedule->command('skubright:begin-request')
                 ->everyMinute();

        /**
         * update-data-request initiates _GET_MERCHANT_LISTINGS_DATA_ and _GET_AFN_INVENTORY_DATA_ requests
         * and inserts it to Table: amazon_request_queues with a class=report-update.
         */
        $schedule->command('skubright:update-data-request')
                 ->hourly();

        /**
         * process-queue reads Table: amazon_request_queues and follows through Amazon using request_id
         * If the report is available, it fetches the flat-file report, parses it,
         * and saves to Table: amazon_merchant_listings.
         *
         * If class == report, it inserts
         * If class == report-update, it updates
         *
         * The flat file report is saved to storage/amazon/ directory.
         *
         * If status == _DONE_, request is deleted from queue and written to Table: amazon_request_histories.
         * If status == _CANCELLED, request is deleted from queue.
         */
        $schedule->command('skubright:process-queue')
                 ->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
