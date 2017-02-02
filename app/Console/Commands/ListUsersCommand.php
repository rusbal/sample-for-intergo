<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class ListUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:list-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users';

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

        $this->info(
            str_pad('ID', 5)
            . str_pad('NAME', 35)
            . str_pad('MWS', 20)
            . str_pad('LISTING', 10)
            . str_pad('REQUEST', 10)
        );

        foreach ($users as $user) {

            $mws = $user->amazonMws;
            $mwsStatus = $mws->valid === 1 ? 'OK' : ($mws->valid === 0 ? '--- Not validated' : '--- Not set');

            $listing = $user->amazonMerchantListing;
            $listingCount = $listing->count();

            $request = $user->amazonRequestHistory();
            $requestCount = $request->count();

            $this->info(
                str_pad($user->id, 5)
                . str_pad($user->name, 35)
                . str_pad($mwsStatus, 20)
                . str_pad($listingCount, 7, ' ', STR_PAD_LEFT) . '   '
                . str_pad($requestCount, 7, ' ', STR_PAD_LEFT) . '   '
            );
        }
    }
}
