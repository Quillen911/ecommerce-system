<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function error($message, $code = 400)
    {
        return response()->json(['error' => $message], $code);
    }

    public static function success($message, $data = [], $code =200)
    {
        return response()->json(['message' => $message, 'data' => $data], $code);
    }
    
    public static function notFound($message, $code = 404)
    {
        return response()->json(['error' => $message], $code);
    }
}