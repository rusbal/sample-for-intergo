<?php

namespace App\Console\Commands;

use App\AmazonRequestQueue;
use App\User;
use Illuminate\Console\Command;

class ShowRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:show-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays all requests';

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
        $rows = AmazonRequestQueue::withoutGlobalScopes()->get();

        $this->info(
            str_pad('STORE', 7) .
            str_pad('NAME', 30) .
            str_pad('REQUEST ID', 15) .
            str_pad('TYPE', 30) .
            str_pad('CREATED', 15) .
            'STATUS'
        );

        foreach ($rows as $row) {
            /**
             * Get user matching store_name = user_id
             */
            $user = User::find($row->store_name);

            $this->info(
                str_pad($row->store_name,7) .
                str_pad($user->name, 30) .
                str_pad($row->request_id, 15) .
                str_pad($row->type, 30) .
                str_pad($row->created_at->diffForHumans(), 15) .
                ($row->pause ? 'paused' : 'active')
            );
        }
    }
}
