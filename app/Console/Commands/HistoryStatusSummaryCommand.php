<?php

namespace App\Console\Commands;

use App\AmazonMws;
use App\AmazonRequestHistory;
use App\User;
use Illuminate\Console\Command;

class HistoryStatusSummaryCommand extends Command
{
    protected $userLatestHistory;

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

    private function printUserSummary($userId, $userSummary, $reports, $typeLengths)
    {
        $this->info('');
        $this->info($userId . '   ' . User::find($userId)->name);

        $statuses = ['_DONE_', '_CANCELLED_'];

        $this->userLatestHistory = $this->getLatestHistory($userId);

        foreach ($statuses as $status) {
            $this->printStatus($reports, $typeLengths, $status, $userSummary);
        }
    }

    private function getLatestHistory($userId)
    {
        /**
         * @var User $user
         */
        $user = User::find($userId);

        return array_map(function($reportType) use($user) {
            return [ $reportType => $user->latestHistory($reportType)->status ];
        }, AmazonMws::REPORT_REQUEST_TYPES);
    }

    private function printStatus($reports, $typeLengths, $status, $userSummary)
    {
        $leftMargin  = str_repeat(' ', 7);
        $labelStatus = $leftMargin . str_pad($status, 15);

        $this->info($labelStatus
            . $this->getStatusValues($reports, $typeLengths, $status, $userSummary)
        );
    }

    private function getStatusValues($reports, $typeLengths, $status, $userSummary)
    {
        $statValues = '';

        foreach ($reports as $idx => $reportType) {
            $length = $typeLengths[$idx];

            if ($idx == 0) {
                /**
                 * First item, decrease by length of label
                 */
                $length -= 15;
            }

            $statValues .= $this->getStatusSummaryForReportType($status, $reportType, $userSummary, $length);

            $statValues .= str_repeat(' ', 3);
        }

        return $statValues;
    }

    private function getStatusSummaryForReportType($status, $reportType, $userSummary, $length)
    {
        $count = 0;

        if (isset($userSummary[$reportType])) {
            if (isset($userSummary[$reportType][$status])) {
                $count = $userSummary[$reportType][$status];
            }
        }

        $count .= $this->userLatestHistory[$reportType] == $status ? '+' : ' ';

        return str_pad($count, $length, ' ', STR_PAD_LEFT);
    }

    private function getRequestTypeLengths($types)
    {
        return array_map('strlen',$types);
    }

    private function printHeader($reports)
    {
        $this->info("USER   " . implode("   ", $reports));
    }
}
