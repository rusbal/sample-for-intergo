<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Four13\Date;
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

    /**
     * Returns a view report of revenue for the previous day
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dailyRevenue()
    {
        return $this->reportViewFor(1, Carbon::yesterday());
    }

    /**
     * Returns a view report of revenue for custom dates
     *
     * @param string $startYmd '2017-01-31'
     * @param string $endYmd   '2017-01-31'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customDateRevenue($startYmd, $endYmd)
    {
        $defaultTimestamp = time() - 86400;
        list($startDate, $endDate, $nDays) = Date::failsafeDateScope($startYmd, $endYmd, $defaultTimestamp);

        return $this->reportViewFor($nDays, $startDate, $endDate);
    }

    /**
     * Private
     */

    /**
     * @param integer $nDays
     * @param Carbon $startDate
     * @param Carbon|null $endDate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function reportViewFor($nDays, $startDate, $endDate = null)
    {
        $reportTitle = reportTitle('Daily Revenue on', $startDate, 'Revenue', $endDate);

        /**
         * @var $reportData array
         *   'summary' =>
         *      (object) ['total_amount' => 1983.45']
         *   'rows' =>
         *      (object) ['item' => 'Nike Zoom Rival S 8 Mens', 'asin' => 'B01A9UQY1Y', 'quantity' => 1, 'amount' => 59.97],
         */
        $reportData = DailyRevenue::fetch($this->user, $startDate, $nDays);

        $startYmd = $startDate->format('Y-m-d');
        $endYmd   = $endDate ? $endDate->format('Y-m-d') : $startYmd;

        return view('report.revenue', compact('reportData', 'reportTitle', 'startYmd', 'endYmd'));
    }
}
