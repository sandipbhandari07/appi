<?php

namespace App\Helper;

class ResponseHelper
{
    public static function success($status = 'success', $message = null, $data = [], $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $statusCode);
    }

    public static function error($status = 'error', $message = null, $data = [], $statusCode = 400)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $statusCode);
    }
}
