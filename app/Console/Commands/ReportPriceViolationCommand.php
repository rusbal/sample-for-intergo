<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Four13\Reports\PriceViolation;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriceViolationReportGenerated;

class ReportPriceViolationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:report-price-violation
                            {user? : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and sends email containing price violation';

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

        $this->info("Price Violation Report");
        $this->info("-------------------------------------------");

        foreach ($users as $user) {
            if (! $user->amazonMws->valid) {
                $this->info("    No valid Amazon MWS: {$user->name}");
                continue;
            }

            $today = Carbon::today();

            if ($rows = $this->processUser($user, $today)) {
                PriceViolation::setAsNotified('\App\AmazonMerchantListingPriceViolation', $user->id, $today);
            }
        }
    }

    // PRIVATE

    private function processUser($user, $date)
    {
        $reportTitle = 'Price Violation [' . $date->format('n/d/y') . ']';
        $reportData = PriceViolation::fetchForEmail($user, $date);

        if ($reportData['count'] > 0) {
            Mail::to($user)->send(new PriceViolationReportGenerated($reportData, $reportTitle));
            $this->info("*** Price violation report email sent: {$user->name}");
        } else {
            $this->info("    No price violation for {$user->name}");
        }

        return $reportData['rows'];
    }
}
