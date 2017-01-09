<?php

namespace App\Http\Controllers;

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
        if (Auth::user()->amazonMws->isNull()) {
            $this->createAmazonMws($request);
        } else {
            $this->updateAmazonMws($request);
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
    }

    private function createAmazonMws(Request $request)
    {
        Auth::user()->amazonMws()->create(
            $this->untokenize($request)
        );
    }
}
