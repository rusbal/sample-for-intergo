<?php

namespace App\Console\Commands;

use App\AmazonRequestHistory;
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

        if ($rows->count() > 0) {
            $this->showRequest($rows);
        } else {
            $this->info("-- No request to show --\n");
            $this->showLatestHistory();
        }
    }

    /**
     * Private
     */

    private function showLatestHistory()
    {
        $rows = AmazonRequestHistory::where('created_at', '>=', \Carbon\Carbon::now()->subHour())->get();

        if ($rows->count() == 0) {
            $this->info("-- No request in the previous hour --");
            return;
        } else {
            $this->info("-- Successful requests in the previous hour --");
        }

        $this->info(
            str_pad('STORE', 7) .
            str_pad('NAME', 30) .
            str_pad('REQUEST ID', 15) .
            str_pad('CLASS', 20) .
            str_pad('TYPE', 30) .
            str_pad('SUCCESS RUN', 15)
        );

        foreach ($rows as $row) {
            $this->info(
                str_pad($row->store_name,7) .
                str_pad($row->user->name, 30) .
                str_pad($row->request_id, 15) .
                str_pad($row->class, 20) .
                str_pad($row->type, 30) .
                str_pad($row->created_at->diffForHumans(), 15)
            );
        }
    }

    private function showRequest($rows)
    {
        $this->info(
            str_pad('STORE', 7) .
            str_pad('NAME', 30) .
            str_pad('REQUEST ID', 15) .
            str_pad('CLASS', 20) .
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
                str_pad($row->class, 20) .
                str_pad($row->type, 30) .
                str_pad($row->created_at->diffForHumans(), 15) .
                ($row->pause ? 'deactivated' : '*** On Queue ***')
            );
        }
    }
}
