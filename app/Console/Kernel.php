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
        Commands\QueueProcessorCommand::class,
        Commands\CallProcessorCommand::class,
        Commands\BeginRequestCommand::class,
        Commands\PauseRequestsCommand::class,
        Commands\UnpauseRequestsCommand::class,
        Commands\ShowRequestsCommand::class,
        Commands\DiscardRequestsCommand::class,
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
