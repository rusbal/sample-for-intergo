<?php

namespace App\Http\Controllers;

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
        /**
         * @var $reportData array
         *   'summary' =>
         *      (object) ['total_amount' => 1983.45']
         *   'rows' =>
         *      (object) ['item' => 'Nike Zoom Rival S 8 Mens', 'asin' => 'B01A9UQY1Y', 'quantity' => 1, 'amount' => 59.97],
         */
        $reportData = DailyRevenue::fetch($this->user);

        return view('report.daily-report', compact('reportData'));
    }
}
