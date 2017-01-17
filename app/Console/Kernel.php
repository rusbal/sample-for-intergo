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
        $schedule->command('skubright:begin-request')
                 ->everyMinute();

        $schedule->command('skubright:process-queue')
                 ->everyMinute();

        $schedule->command('skubright:update-data-request')
                 ->hourly();
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
