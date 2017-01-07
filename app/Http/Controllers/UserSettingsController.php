<?php

namespace App\Http\Controllers;

use App\AmazonMws;
use App\NullObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    public function index()
    {
        $row = Auth::user()->amazonMws ?: NullObject::create();

        return view('my.settings', compact('row'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $amazonSettings = new AmazonMws(
            $request->all()
        );
        $user->amazonMws()->save($amazonSettings);

        flash('Your Amazon MWS settings was successfully saved.', 'success');

        return redirect()->action('DashboardController@index');
    }
}
