<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UnpauseRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:unpause-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpause or release all requests allowing them to be queued';

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
        DB::table('amazon_request_queues')->update(['pause' => 0]);
    }
}
