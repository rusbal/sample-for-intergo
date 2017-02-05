<?php

namespace Four13\Reports;


use Illuminate\Support\Facades\DB;

class PriceViolation
{
    const SQL = <<<SQL
        SELECT
            ml.item_name,
            pv.asin,
            pv.seller_price,
            pv.minimum_advertized_price,
            pv.merchant_id

        FROM amazon_merchant_listing_price_violations AS pv
        LEFT JOIN amazon_merchant_listings AS ml
          ON ml.asin1 = pv.asin

        WHERE pv.user_id = ?
          AND pv.notification_sent_at IS NULL
          AND (pv.amazon_publish_time BETWEEN ? AND ADDDATE(?, ?))
          
        GROUP BY
          pv.asin,
          pv.merchant_id
        
        ORDER BY
          ml.item_name ASC,
          pv.seller_price DESC
SQL;

    const NAME = 'priceviolation';

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