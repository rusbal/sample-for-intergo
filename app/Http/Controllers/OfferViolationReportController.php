<?php

namespace App\Http\Controllers;

use Four13\Date;
use Carbon\Carbon;
use Four13\Reports\OfferViolation;
use Illuminate\Support\Facades\Auth;

class OfferViolationReportController extends Controller
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
    public function daily()
    {
        return $this->reportViewFor(1, Carbon::today());
    }

    /**
     * Returns a view report of revenue for custom dates
     *
     * @param string $startYmd '2017-01-31'
     * @param string $endYmd   '2017-01-31'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customDate($startYmd, $endYmd)
    {
        $defaultTimestamp = time() - 86400;
        list($startDate, $endDate, $nDays) = Date::failsafeDateScope($startYmd, $endYmd, $defaultTimestamp);

        return $this->reportViewFor($nDays, $startDate, $endDate);
    }

    // PRIVATE

    /**
     * @param integer $nDays
     * @param Carbon $startDate
     * @param Carbon|null $endDate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function reportViewFor($nDays, $startDate, $endDate = null)
    {
        $reportTitle = reportTitle('Offer Violation on', $startDate, 'Offer Violation', $endDate);

        $reportData = OfferViolation::fetch($this->user, $startDate, $nDays);

        $startYmd = $startDate->format('Y-m-d');
        $endYmd   = $endDate ? $endDate->format('Y-m-d') : $startYmd;

        return view('report.offer-violation', compact('reportData', 'reportTitle', 'startYmd', 'endYmd'));
    }
}
