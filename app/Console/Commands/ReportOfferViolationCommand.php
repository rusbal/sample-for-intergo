<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Four13\Reports\OfferViolation;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferViolationReportGenerated;

class ReportOfferViolationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:report-offer-violation
                            {user? : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and sends email containing offer violation';

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

        $this->info("Offer Violation Report");
        $this->info("-------------------------------------------");

        foreach ($users as $user) {
            if (! $user->amazonMws->valid) {
                $this->info("    No valid Amazon MWS: {$user->name}");
                continue;
            }

            $today = Carbon::today();

            if ($rows = $this->processUser($user, $today)) {
                OfferViolation::setAsNotified('\App\AmazonMerchantListingOfferViolation', $user->id, $today);
            }
        }
    }

    // PRIVATE

    private function processUser($user, $date)
    {
        $reportTitle = 'Offer Violation [' . $date->format('n/d/y') . ']';
        $reportData = OfferViolation::fetchForEmail($user, $date);

        if ($reportData['count'] > 0) {
            Mail::to($user)->send(new OfferViolationReportGenerated($reportData, $reportTitle));
            $this->info("*** Offer violation report email sent: {$user->name}");
        } else {
            $this->info("    No offer violation for {$user->name}");
        }

        return $reportData['rows'];
    }
}
