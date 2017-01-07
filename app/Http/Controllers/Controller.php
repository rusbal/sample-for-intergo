<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $requireAuthorizedUserOn = [
        DashboardController::class,
        UserSettingsController::class,
    ];

    public function __construct()
    {
        if (in_array(get_class($this), $this->requireAuthorizedUserOn)) {
            $this->middleware('auth');
        }
    }

    public function untokenize(Request $request)
    {
        $arr = $request->all();
        if (isset($arr['_token'])) {
            unset($arr['_token']);
        }
        return $arr;
    }
}
