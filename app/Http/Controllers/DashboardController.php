<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $inventory = $user->amazonMws->getSupply($user);

        return view('my.dashboard', compact('inventory'));
    }
}
