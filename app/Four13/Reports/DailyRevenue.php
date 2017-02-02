<?php

namespace Four13\Reports;


use App\User;
use Carbon\Carbon;
use App\NullObject;
use Illuminate\Support\Facades\DB;

class DailyRevenue
{
    const SQL = <<<SQL
        SELECT
            ml.item_name         AS item,
            oid.asin,
            oid.order_quantity   AS quantity,
            ml.quantity_available,
            oid.order_item_price AS amount

        FROM amazon_order_details AS od
        JOIN amazon_order_item_details AS oid
            ON oid.amazon_order_id = od.amazon_order_id
        LEFT JOIN amazon_merchant_listings AS ml
            ON ml.asin1 = oid.asin

        WHERE od.merchant_id = ?
        AND (od.purchase_date BETWEEN ? AND ADDDATE(?, ?))

        ORDER BY oid.order_item_price DESC
SQL;

    const SUMMARY = <<<SQL
        SELECT
            SUM(oid.order_item_price) AS total_amount

        FROM amazon_order_details AS od
        JOIN amazon_order_item_details AS oid
            ON oid.amazon_order_id = od.amazon_order_id

        WHERE od.merchant_id = ?
        AND (od.purchase_date BETWEEN ? AND ADDDATE(?, ?))
SQL;

    const NAME = 'dailyrevenue';

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
     *   'summary' =>
     *      (object) ['total_amount' => 1983.45']
     *   'rows' =>
     *      (object) ['item' => 'Nike Zoom Rival S 8 Mens', 'asin' => 'B01A9UQY1Y', 'quantity' => 1, 'amount' => 59.97],
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
        $report = $self->generate();

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
        $rows = [];
        $summary = NullObject::create();

        $merchantId = $this->user->amazonMws->merchant_id;

        if ($merchantId) {

            $queryParams = [
                $merchantId,
                $this->startDate,
                $this->startDate,
                $this->nDays
            ];

            $summaryRows = DB::select(self::SUMMARY, $queryParams);

            if ($summaryRows) {
                $summary = $summaryRows[0];
            }

            $rows = DB::select(self::SQL, $queryParams);
        }

        return [
            'rows'    => $rows,
            'summary' => $summary,
        ];
    }
}