<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use Illuminate\Support\Arr;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request): ?JsonResponse {
            if (!($request->expectsJson() || $request->is('api/*'))) {
                return null; // web istekleri için Laravel’in varsayılan davranışı
            }

            if ($e instanceof ValidationException) {
                $firstMessage = Arr::first(Arr::flatten($e->errors())) ?? __('validation.failed');
                $firstMessage = preg_replace('/\s*\(and\s+\d+\s+more\s+errors?\)\s*$/i', '', $firstMessage);

                return response()->json([
                    'message' => $firstMessage,
                ], 422);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Kimlik doğrulaması gerekli.',
                ], 401);
            }

            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'İstek gerçekleşirken bir hata oluştu.',
                ], $e->getStatusCode());
            }

            if ($e instanceof \RuntimeException) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            // Diğer tüm hatalar için 500
            return response()->json([
                'success' => false,
                'error'   => class_basename($e),
                'message' => $e->getMessage(),
            ], 500);
        });
    }
}
