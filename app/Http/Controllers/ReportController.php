<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Four13\Reports\DailyRevenue;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function dailyRevenue()
    {
        $reportTitle = 'Daily Revenue [' . date('n/d/y', time() - 86400) . ']';

        /**
         * @var $reportData array
         *   'summary' =>
         *      (object) ['total_amount' => 1983.45']
         *   'rows' =>
         *      (object) ['item' => 'Nike Zoom Rival S 8 Mens', 'asin' => 'B01A9UQY1Y', 'quantity' => 1, 'amount' => 59.97],
         */
        $reportData = DailyRevenue::fetch($this->user, Carbon::yesterday(), 1);

        $startYmd = Carbon::yesterday()->format('Y-m-d');
        $endYmd   = $startYmd;

        return view('report.revenue', compact('reportData', 'reportTitle', 'startYmd', 'endYmd'));
    }

    public function customDateRevenue($startYmd, $endYmd)
    {
        list($startDate, $endDate, $nDays) = $this->failsafeDateScope($startYmd, $endYmd);

        $reportTitle = $this->reportTitle($startDate, $endDate);
        $reportData = DailyRevenue::fetch($this->user, $startDate, $nDays);

        $startYmd = $startDate->format('Y-m-d');
        $endYmd   = $endDate->format('Y-m-d');

        return view('report.revenue', compact('reportData', 'reportTitle', 'startYmd', 'endYmd'));
    }

    /**
     * Private
     */

    /**
     * @param Carbon $startDate
     * @param Carbon|null $endDate
     * @return string
     */
    private function reportTitle($startDate, $endDate = null)
    {
        $dateScope = $startDate->format('n/d/y');

        if ($endDate) {
            $dateScope .= ' to ' . $endDate->format('n/d/y');
        }

        return "Revenue [$dateScope]";
    }

    private function failsafeDateScope($startYmd, $endYmd)
    {
        $defaultTimestamp = time() - 86400;

        $startDate = $this->failsafeDate($startYmd, $defaultTimestamp);
        $endDate   = $this->failsafeDate($endYmd, $defaultTimestamp);

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
    private function failsafeDate($date, $timestamp)
    {
        try {
            $validDate = Carbon::parse($date);

        } catch (\Exception $exception) {
            $startYmd = date('Y-m-d', $timestamp);
            $validDate = Carbon::parse($startYmd);
        }

        return $validDate;
    }
}
