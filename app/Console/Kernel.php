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
        Commands\RequestListingForUserCommand::class,

        Commands\ShowRequestsCommand::class,
        Commands\ShowInvalidSkuCommand::class,
        Commands\ShowInventoryFetchCommand::class,

        Commands\DailyReportCommand::class,
        Commands\ReportCacheClearCommand::class,
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
         * `skubright:begin-request` checks the following:
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
         * `skubright:update-data-request` initiates _GET_MERCHANT_LISTINGS_DATA_ and _GET_AFN_INVENTORY_DATA_ requests
         * and inserts it to Table: amazon_request_queues with a class=report-update.
         */
        $schedule->command('skubright:update-data-request')
                 ->hourly();

        /**
         * `skubright:process-queue` queues Job: ProcessAmazonQueue
         * which is then picked up by the `queue:listen` that runs forever being managed by supervisord.
         *
         * /etc/supervisor/conf.d/queue.conf
         *  [program:queue]
         *  command=php /home/raymond/Sites/skubright/www/artisan queue:listen --tries=2
         *  directory=/home/raymond/Sites/skubright/www
         *  stdout_logfile=/home/raymond/Sites/skubright/www/storage/logs/supervisor.log
         *  redirect_stderr=true
         *
         * ProcessAmazonQueue reads Table: amazon_request_queues and follows through Amazon using request_id
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
         *
         * *** CONSIDERING... ***
         * `skubright:poke-amazon` is doing almost exactly as `skubright:process-queue`
         * Small differences:
         * 1. poke-amazon does not queue, process-queue does queue by calling Job: ProcessAmazonQueue
         *    (but is there a need when it's only run on the background by cronjob?)
         *    poke-amazon code is similar to ProcessAmazonQueue.  process-queue is only a job dispatcher.
         * 2. poke-amazon does not process empty request_id, process-queue does.
         * Difference in purpose:
         * - poke-amazon is designed for manual run on the command line for existing requests.
         *    It was initially used for debugging.
         * - process-queue is designed to handle all requests
         *    (even ones not successfully initiated, ie., empty request_id)
         *    (** empty request_id was caused by unsuccessful initial request
         *        because of not supplying mws_auth_token. So this will not happen anymore.)
         */
        $schedule->command('skubright:process-queue')
                 ->everyMinute();

        $schedule->command('skubright:report-revenue-daily')
                 ->dailyAt('00:01');
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
