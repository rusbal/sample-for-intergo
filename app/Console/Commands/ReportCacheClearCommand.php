<?php

namespace App\Console\Commands;

use App\ReportCache;
use Illuminate\Console\Command;

class ReportCacheClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:report-clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear report caches';

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
        ReportCache::query()->truncate();
    }
}
