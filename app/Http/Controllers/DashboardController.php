<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->amazonRequestHistory->count() == 0) {
            /**
             * requestListing() only executes if amazonMws settings exists.
             * If it does not exist, amazonMws is NullObject.
             */
            $user->amazonMws->requestListing();
        }

        $listing = null;

        return view('my.dashboard', compact('listing'));
    }
}
