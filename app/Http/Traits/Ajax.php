<?php

namespace App\Http\Traits;


trait Ajax
{
    public function success($message = null)
    {
        return $this->status(true, $message);
    }

    public function failure($message = null)
    {
        return $this->status(false, $message);
    }

    public function status($isSuccess = false, $message)
    {
        return [
            'success' => $isSuccess,
            'message' => $message,
        ];
    }
}