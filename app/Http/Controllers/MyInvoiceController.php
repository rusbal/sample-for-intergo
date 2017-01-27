<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MyInvoiceController extends Controller
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

    public function index()
    {
        return view('my.invoice');
    }
}