<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateListing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $inventory = $user->amazonMws->getSupply();

        return view('my.dashboard', compact('inventory'));
    }

    public function report()
    {
//        $user = Auth::user();
//        $report = $user->amazonMws->getListing();

        $job = new GenerateListing;
        $this->dispatch($job);
        return 'done';
    }
}
