<?php

namespace Four13\Reports;


use App\NullObject;
use Illuminate\Support\Facades\DB;

class DailyRevenue
{
    const SQL = <<<SQL
        SELECT
            ml.item_name         AS item,
            oid.asin,
            oid.order_quantity   AS quantity,
            oid.order_item_price AS amount

        FROM amazon_order_details AS od
        JOIN amazon_order_item_details AS oid
            ON oid.amazon_order_id = od.amazon_order_id
        LEFT JOIN amazon_merchant_listings AS ml
            ON ml.asin1 = oid.asin

        WHERE od.merchant_id = ?
        AND DATE(od.purchase_date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)

        ORDER BY oid.order_item_price DESC
SQL;

    const SUMMARY = <<<SQL
        SELECT
            SUM(oid.order_item_price) AS total_amount

        FROM amazon_order_details AS od
        JOIN amazon_order_item_details AS oid
            ON oid.amazon_order_id = od.amazon_order_id

        WHERE od.merchant_id = ?
        AND DATE(od.purchase_date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
SQL;


    public static function fetch($user)
    {
        $rows = [];
        $summary = NullObject::create();

        $merchantId = $user->amazonMws->merchant_id;

        if ($merchantId) {
            if ($summaryRows = DB::select(self::SUMMARY, [$merchantId])) {
                $summary = $summaryRows[0];
            }
            $rows = DB::select(self::SQL, [$merchantId]);
        }

        return [
            'rows'    => $rows,
            'summary' => $summary,
        ];
    }
}