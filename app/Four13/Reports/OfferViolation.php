<?php

namespace Four13\Reports;


class OfferViolation extends Violation
{
    const WEB_SQL = <<<SQL
        SELECT
            ml.item_name,
            ov.asin,
            ov.offer_quantity,
            ov.maximum_offer_quantity

        FROM amazon_merchant_listing_offer_violations AS ov
        LEFT JOIN amazon_merchant_listings AS ml
          ON ml.asin1 = ov.asin

        WHERE ov.user_id = ?
          AND (ov.amazon_publish_time BETWEEN ? AND ADDDATE(?, ?))
          
        GROUP BY ov.asin
        
        ORDER BY
          ml.item_name ASC
SQL;

    const EMAIL_SQL = <<<EMAIL
        SELECT SQL_CALC_FOUND_ROWS
            ov.id,
            ml.item_name,
            ov.asin,
            ov.offer_quantity,
            ov.maximum_offer_quantity

        FROM amazon_merchant_listing_offer_violations AS ov
        LEFT JOIN amazon_merchant_listings AS ml
          ON ml.asin1 = ov.asin

        WHERE ov.user_id = ?
          AND ov.amazon_publish_time >= ?
          AND ov.notification_sent_at IS NULL
          
        GROUP BY ov.asin
        
        ORDER BY
          ml.item_name ASC

        LIMIT ?
EMAIL;

    const NAME = 'offerviolation';
}