<?php

namespace Four13\Reports;


use App\ReportCache;

class Cache
{
    protected $user;
    protected $reportType;
    protected $startDate;
    protected $nDays;
    protected $uniqueId;

    public function __construct($user, $reportType, $startDate, $nDays = 1)
    {
        $this->user = $user;
        $this->reportType = $reportType;
        $this->startDate = $startDate;
        $this->nDays = $nDays;
        $this->uniqueId = $this->setUniqueId();
    }

    public function save($data)
    {
        ReportCache::create([
            'date_days_report_user_id' => $this->uniqueId,
            'data' => serialize($data),
            'start_date' => $this->startDate,
            'report' => $this->reportType,
            'n_days' => $this->nDays ?: 0,
            'user_id' => $this->user->id,
        ]);
    }

    public function retrieve()
    {
        $report = ReportCache::where('date_days_report_user_id', $this->uniqueId)->first();

        if (! $report) {
            return false;
        }

        return unserialize($report->data);
    }

    public function invalidate()
    {
        return ReportCache::where('date_days_report_user_id', $this->uniqueId)->delete();
    }

    /**
     * Private
     */

    private function setUniqueId()
    {
        if (! $this->startDate)  throw new \Exception("Invalid call to Reports/Cache@uniqueId. Invalid start date.");
        if (! $this->nDays)      throw new \Exception("Invalid call to Reports/Cache@uniqueId. Invalid number of days.");
        if (! $this->reportType) throw new \Exception("Invalid call to Reports/Cache@uniqueId. Invalid report type.");
        if (! $this->user)       throw new \Exception("Invalid call to Reports/Cache@uniqueId. Invalid user.");

        return implode('.', [
            $this->startDate->format('Ymd'),
            $this->nDays,
            $this->reportType,
            $this->user->id,
        ]);
    }
}