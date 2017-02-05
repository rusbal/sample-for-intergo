<?php

namespace Four13\Reports;


use Illuminate\Support\Facades\DB;

class OfferViolation
{
    const SQL = <<<SQL
        SELECT
            ml.item_name,
            ov.asin,
            ov.offer_quantity,
            ov.maximum_offer_quantity

        FROM amazon_merchant_listing_offer_violations AS ov
        LEFT JOIN amazon_merchant_listings AS ml
          ON ml.asin1 = ov.asin

        WHERE ov.user_id = ?
          AND ov.notification_sent_at IS NULL
          AND (ov.amazon_publish_time BETWEEN ? AND ADDDATE(?, ?))
          
        GROUP BY ov.asin
        
        ORDER BY
          ml.item_name ASC
SQL;

    const NAME = 'offerviolation';

    protected $user;
    protected $nDays;
    protected $startDate;

    public function __construct($user, $startDate, $nDays)
    {
        $this->user = $user;
        $this->nDays = $nDays;
        $this->startDate = $startDate->format('Y-m-d');
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
        $cache = new Cache($user, self::NAME, $startDate, $nDays);

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
        $cache->save($report);

        return $report;
    }

    /**
     * Private
     */

    private function generate()
    {
        $queryParams = [
            $this->user->id,
            $this->startDate,
            $this->startDate,
            $this->nDays
        ];

        return DB::select(self::SQL, $queryParams);
    }
}