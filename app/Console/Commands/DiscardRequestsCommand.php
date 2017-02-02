<?php

namespace App\Console\Commands;

use App\AmazonRequestQueue;
use Illuminate\Console\Command;

class DiscardRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:discard-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all requests';

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
        AmazonRequestQueue::getQuery()->delete();
        $this->call('skubright:show-requests');
    }
}
