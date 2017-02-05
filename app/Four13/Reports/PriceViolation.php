<?php

namespace Four13\Reports;


class PriceViolation extends Violation
{
    const WEB_SQL = <<<SQL
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
          AND (pv.amazon_publish_time BETWEEN ? AND ADDDATE(?, ?))
          
        GROUP BY
          pv.asin,
          pv.merchant_id
        
        ORDER BY
          ml.item_name ASC,
          pv.seller_price DESC
SQL;

    const EMAIL_SQL = <<<EMAIL
        SELECT SQL_CALC_FOUND_ROWS
            pv.id,
            ml.item_name,
            pv.asin,
            pv.seller_price,
            pv.minimum_advertized_price,
            pv.merchant_id

        FROM amazon_merchant_listing_price_violations AS pv
        LEFT JOIN amazon_merchant_listings AS ml
          ON ml.asin1 = pv.asin

        WHERE pv.user_id = ?
          AND pv.amazon_publish_time >= ?
          AND pv.notification_sent_at IS NULL
          
        GROUP BY
          pv.asin,
          pv.merchant_id
        
        ORDER BY
          ml.item_name ASC,
          pv.seller_price DESC

        LIMIT ?
EMAIL;

    const NAME = 'priceviolation';
}