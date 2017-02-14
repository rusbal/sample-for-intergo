<?php

namespace Four13\Reports;


use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Violation
{
    protected $user;
    protected $nDays;
    protected $startDate;

    public function __construct($user, $startDate, $nDays)
    {
        $this->user = $user;
        $this->nDays = $nDays;
        $this->startDate = $startDate;
    }

    public static function fetchForEmail($user, $startDate, $limit = 100)
    {
        $adjHours = config('app.adjust_hours');

        $queryParams = [
            $user->id,
            $startDate->addHours($adjHours),
            $limit,
        ];

        $rows  = DB::select(static::EMAIL_SQL, $queryParams);
        $count = DB::select('SELECT FOUND_ROWS() AS count')[0]->count;

        return [
            'rows'  => $rows,
            'count' => $count,
        ];
    }

    public static function setAsNotified($model, $userId, $date)
    {
        $model::where('user_id', $userId)
            ->where('amazon_publish_time', '>=', $date)
            ->update([ 'notification_sent_at' => Carbon::now() ]);
    }

    /**
     * Returns report data either by generation or returning from cache.
     *
     * @param User $user
     * @param Carbon $startDate
     * @param integer $nDays
     * @param boolean $invalidateCache
     * @return array
     */
    public static function fetch($user, $startDate, $nDays = 1, $invalidateCache = false)
    {
        $cache = new Cache($user, static::NAME, $startDate, $nDays);

        if ($invalidateCache) {
            $cache->invalidate();

        } elseif ($report = $cache->retrieve()) {
            /**
             * Return cached report if already generated
             */
            return $report;
        }

        /**
         * Generate report
         */
        $self = new static($user, $startDate, $nDays);

        $report = [
            'rows' => $self->generate()
        ];

        /**
         * Save report to cache
         */
        //$cache->save($report);

        return $report;
    }

    /**
     * Private
     */

    private function generate()
    {
        $adjHours = config('app.adjust_hours');

        $startDte = $this->startDate->addHours($adjHours);

        $queryParams = [
            $this->user->id,
            $startDte,
            $startDte,
            $this->nDays
        ];

        return DB::select(static::WEB_SQL, $queryParams);
    }
}