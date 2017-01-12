<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessAmazonQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class QueueProcessorCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:process-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the Amazon MWS request queue.';

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
        $job = new ProcessAmazonQueue;
        $this->dispatch($job);
        $this->info('Job: ProcessAmazonQueue executed.');
    }
}
