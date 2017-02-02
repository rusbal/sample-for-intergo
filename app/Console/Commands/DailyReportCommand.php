<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Four13\Reports\DailyRevenue;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyRevenueReportGenerated;

class DailyReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:report-revenue-daily
                            {user? : The ID of the user}';

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
        $userId = $this->argument('user');

        if ($userId) {
            $users = User::where('id', $userId)->get();
        } else {
            $users = User::all();
        }

        $this->info("Daily Revenue Report");
        $this->info("-------------------------------------------");

        foreach ($users as $user) {
            if (! $user->amazonMws->valid) {
                $this->info("    No valid Amazon MWS: {$user->name}");
                continue;
            }

            $reportTitle = 'Daily Revenue [' . date('n/d/y', time() - 86400) . ']';
            $reportData = DailyRevenue::fetch($user, Carbon::yesterday(), 1, true);

            Mail::to($user)->send(new DailyRevenueReportGenerated($reportData, $reportTitle));
            $this->info("*** Daily revenue report email sent: {$user->name}");
        }
    }
}
