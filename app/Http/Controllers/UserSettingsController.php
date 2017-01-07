<?php

namespace App\Http\Controllers;

use App\AmazonMws;
use App\NullObject;
use App\User;
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
        if (Auth::user()->amazonMws) {
            $this->updateAmazonMws($request);
        } else {
            $this->createAmazonMws($request);
        }

        return redirect()->action('DashboardController@index');
    }

    /**
     * Private functions
     */

    private function updateAmazonMws(Request $request)
    {
        Auth::user()->amazonMws()->update(
            $this->untokenize($request)
        );
        flash('Your Amazon MWS settings was successfully updated.', 'success');
    }

    private function createAmazonMws(Request $request)
    {
        Auth::user()->amazonMws()->create(
            $this->untokenize($request)
        );
        flash('Your Amazon MWS settings was successfully created.', 'success');
    }
}
