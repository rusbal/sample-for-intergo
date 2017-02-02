<?php

namespace App\Console\Commands;

use App\AmazonMws;
use App\AmazonRequestHistory;
use Illuminate\Console\Command;

class HistoryStatusSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skubright:history-status
                            {user? : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows history status totals';

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

        $builder = AmazonRequestHistory::withoutGlobalScopes()
            ->select('user_id', 'type', 'status', \DB::raw("count(id) AS count"))
            ->groupBy(['user_id', 'type', 'status']);

        if ($userId) {
            $builder->where(['user_id', $userId]);
        }

        $rows = $builder->get();

        $summaries = $this->summarizePerUser($rows);
        $this->showHistoryStatus($summaries);
    }

    // PRIVATE

    private function summarizePerUser($rows)
    {
        $out = [];

        foreach ($rows as $row) {
            if (! isset($out[$row->user_id])) {
                $out[$row->user_id] = [];
            }

            if (! isset($out[$row->user_id][$row->type])) {
                $out[$row->user_id][$row->type] = [];
            }

            $out[$row->user_id][$row->type][$row->status] = $row->count;
        }

        return $out;
    }

    private function showHistoryStatus($summaries)
    {
        $reports = AmazonMws::REPORT_REQUEST_TYPES;
        $typeLengths = $this->getRequestTypeLengths($reports);

        $this->printHeader($reports);

        foreach ($summaries as $userId => $summary) {
            $this->printUserSummary($userId, $summary, $reports, $typeLengths);
        }
    }

    private function getRequestTypeLengths($types)
    {
        return array_map('count',$types);
    }

    private function printHeader($reports)
    {
        $this->info("USER   " . implode("   ", $reports));
    }

    private function printUserSummary($userId, $userSummary, $reports, $typeLengths)
    {
        $details = str_pad($userId, 7);
        $this->info($details);

//        foreach ($userSummary as $type => $row) {
//            $line = str_pad($type, 10);
//
//            foreach ($row as $status => $count) {
//                $line .= str_pad($status, 10);
//                $line .= str_pad($count, 10);
//            }
//
//            $this->info($line);
//        }

        foreach ($reports as $idx => $reportType) {
            $len = $typeLengths[$idx];

            $line = '';

            if (isset($userSummary[$reportType])) {
                foreach ($userSummary[$reportType] as $status => $count) {
                    $line .= str_pad($status, 20);
                    $line .= str_pad($count, 10);
                }
            }

            $outline = str_pad($line, $len);

            $this->info($outline);
        }
    }
}
