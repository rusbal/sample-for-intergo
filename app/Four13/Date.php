<?php

namespace Four13;


use Carbon\Carbon;

class Date
{

    /**
     * Returns failsafe date scope that uses default timestamp when date parameters are invalid.
     *
     * @param string $startYmd '2017-01-31'
     * @param string $endYmd   '2017-01-31'
     * @param integer $defaultTimestamp
     * @return array [Carbon, Carbon, integer]
     */
    public static function failsafeDateScope($startYmd, $endYmd, $defaultTimestamp)
    {
        $startDate = self::failsafeDate($startYmd, $defaultTimestamp);
        $endDate   = self::failsafeDate($endYmd, $defaultTimestamp);

        if ($startDate > $endDate) {
            /**
             * End date is earlier, not good, let's fix it.
             */
            $holdDate = $endDate;
            $endDate = $startDate;
            $startDate = $holdDate;
        }

        $nDays = $endDate->diffInDays($startDate);

        /**
         * Same start and end date means 1 day.  Let's adjust.
         */
        $nDays += 1;

        return [$startDate, $endDate, $nDays];
    }

    /**
     * Returns default date if parameter is invalid date string
     *
     * @param string $date
     * @param integer $timestamp Default date
     * @return Carbon
     */
    public static function failsafeDate($date, $timestamp)
    {
        try {
            $validDate = Carbon::parse($date);

        } catch (\Exception $exception) {
            $validDate = Carbon::createFromTimestamp($timestamp);
        }

        /**
         * Do not allow future date for report generation
         */
        if ($validDate->isFuture()) {
            $validDate = Carbon::now();
        }

        return $validDate;
    }
}