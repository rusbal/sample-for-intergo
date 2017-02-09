<?php

namespace App\Console\Commands;

use App\Notifications\ProgrammerTestNotification;
use App\User;
use Illuminate\Console\Command;

class ProgrammerTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'programmertest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Programmer test command';

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
        $programmerName = 'Raymond Usbal';
        $user = User::where('name', $programmerName)->first();

        if (! $user) {
            $this->info("Programmer name not found: '$programmerName'");
            return;
        }

        $user->notify(new ProgrammerTestNotification());
        $this->info("Programmer notification sent to $user->email");
    }
}
