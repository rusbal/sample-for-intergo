<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyRevenueReportGenerated;

class DailyReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:report-revenue-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and sends email containing daily revenue';

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

        $this->info("Daily Revenue Report");
        $this->info("-------------------------------------------");

        foreach ($users as $user) {
            if (! $user->amazonMws->valid) {
                $this->info("    No valid Amazon MWS: {$user->name}");
                continue;
            }

            $reportData = DailyRevenue::fetch($this->user, Carbon::yesterday(), 1);

            Mail::to($user)->send(new DailyRevenueReportGenerated($reportData));
            $this->info("*** Daily revenue report email sent: {$user->name}");
        }
    }
}
