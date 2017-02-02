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
    protected $signature = 'skubright:show-requests
                            {--all : Whether to list all requests}
                            {--active : Whether to list current requests only}
                            {--history : Whether to list history requests only}';

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
        $isAll = $this->option('all');
        $isActive = $this->option('active');
        $isHistory = $this->option('history');

        if ($isAll) {
            $showCurrent = true;
            $showHistory = true;

        } elseif ($isActive) {
            $showCurrent = true;
            $showHistory = false;

        } elseif ($isHistory) {
            $showCurrent = false;
            $showHistory = true;

        } else {
            $showCurrent = true;
            $showHistory = false;
        }

        if ($showCurrent) {
            $this->showRequest();
        }

        if ($isAll) {
            $this->info('');
        }

        if ($showHistory) {
            $this->showLatestHistory();
        }
    }

    /**
     * Private
     */

    private function showLatestHistory()
    {
        $rows = AmazonRequestHistory::withoutGlobalScopes()
            ->where('created_at', '>=', \Carbon\Carbon::now()->subHour())
            ->get();

        if ($rows->count() == 0) {
            $this->info("-- No request history in the previous 60 minutes --");
            return;
        } else {
            $this->info("-- Request history in the previous 60 minutes --");
        }

        $this->info(
            str_pad('STORE', 7) .
            str_pad('NAME', 30) .
            str_pad('REQUEST ID', 15) .
            str_pad('CLASS', 20) .
            str_pad('TYPE', 50) .
            str_pad('WHEN', 20) .
            'STATUS'
        );

        foreach ($rows as $row) {
            $this->info(
                str_pad($row->store_name,7) .
                str_pad($row->user->name, 30) .
                str_pad($row->request_id, 15) .
                str_pad($row->class, 20) .
                str_pad($row->type, 50) .
                str_pad($row->created_at->diffForHumans(), 20) .
                $row->status
            );
        }
    }

    private function showRequest()
    {
        $rows = AmazonRequestQueue::withoutGlobalScopes()->get();

        if ($rows->count() == 0) {
            $this->info("-- No request to show --");
            return;
        }

        $this->info(
            str_pad('STORE', 7) .
            str_pad('NAME', 30) .
            str_pad('REQUEST ID', 15) .
            str_pad('CLASS', 20) .
            str_pad('TYPE', 50) .
            str_pad('CREATED', 20) .
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
                str_pad($row->type, 50) .
                str_pad($row->created_at->diffForHumans(), 20) .
                ($row->pause ? 'deactivated' : '*** On Queue ***')
            );
        }
    }
}
