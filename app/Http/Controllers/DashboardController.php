<?php

namespace App\Http\Controllers;

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
        $user = Auth::user();
        $report = $user->amazonMws->getSupply();

        var_dump($report);
    }
}
