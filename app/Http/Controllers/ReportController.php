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
//        $rows = [
//            (object) ['item' => null, 'asin' => 'AFD3DSF899SF', 'quantity' => 1, 'amount' => 10.59],
//            (object) ['item' => 'Name of item 1', 'asin' => 'BGD3DSF899SF', 'quantity' => 2, 'amount' => 110.00],
//            (object) ['item' => 'Name of item 2', 'asin' => 'CRF3DSF899SF', 'quantity' => 3, 'amount' => 9.99],
//            (object) ['item' => 'Name of item 3', 'asin' => 'GEF3DSF899SF', 'quantity' => 4, 'amount' => 11.19],
//            (object) ['item' => 'Name of item 4', 'asin' => '8EF3DSF899SF', 'quantity' => 5, 'amount' => 2.29],
//            (object) ['item' => 'Name of item 5', 'asin' => 'MEF3DSF899SF', 'quantity' => 6, 'amount' => 80.00],
//            (object) ['item' => 'Name of item 6', 'asin' => 'IEF3DSF899SF', 'quantity' => 7, 'amount' => 99.50],
//            (object) ['item' => 'Name of item 7', 'asin' => 'OEF3DSF899SF', 'quantity' => 8, 'amount' => 100.30],
//            (object) ['item' => 'Name of item 8', 'asin' => 'PEF3DSF899SF', 'quantity' => 9, 'amount' => 1.82],
//            (object) ['item' => 'Name of item 9', 'asin' => 'WEF3DSF899SF', 'quantity' => 10, 'amount' => 2.90],
//            (object) ['item' => 'Name of item 10', 'asin' => 'QEF3DSF899SF', 'quantity' => 11, 'amount' => 50.23],
//            (object) ['item' => 'Name of item 11', 'asin' => 'VEF3DSF899SF', 'quantity' => 12, 'amount' => 18.80],
//        ];

        $reportData = DailyRevenue::fetch($this->user);

        return view('report.daily-report', compact('reportData'));
    }
}
