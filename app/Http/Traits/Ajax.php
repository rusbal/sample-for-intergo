<?php

namespace App\Http\Traits;


trait Ajax
{
    public function success($message = null, $array = [])
    {
        return $this->status(true, $message, $array);
    }

    public function failure($message = null, $array = [])
    {
        return $this->status(false, $message, $array);
    }

    public function status($isSuccess = false, $message, $array = [])
    {
        return array_merge([
            'success' => $isSuccess,
            'message' => $message,
        ], $array);
    }
}