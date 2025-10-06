<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // ✅ Tüm exception'ları JSON formatında döndür
        $this->renderable(function (Throwable $e, $request): JsonResponse {
            if ($request->expectsJson() || $request->is('api/*')) {
                $status = 500;

                if ($e instanceof \RuntimeException) {
                    $status = 400;
                }

                return response()->json([
                    'success' => false,
                    'error' => class_basename($e),
                    'message' => $e->getMessage(),
                ], $status);
            }

            return null; // Laravel normal handler'a geçsin
        });
    }
}
