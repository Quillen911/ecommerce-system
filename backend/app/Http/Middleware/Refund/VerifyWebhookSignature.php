<?php

namespace App\Http\Middleware\Refund;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     * Route::post(...)->middleware('verify.refund-webhook:shipment');
     */
    public function handle(Request $request, Closure $next, string $provider = 'default'): Response
    {
        if (! $this->isValidSignature($request, $provider)) {
            abort(401, 'Invalid webhook signature');
        }

        return $next($request);
    }

    private function isValidSignature(Request $request, string $provider): bool
    {
        $secret = config("services.refund_webhooks.providers.{$provider}.secret");

        if (! $secret) {
            return false;
        }

        $signatureHeader = $request->header('X-Signature');
        if (blank($signatureHeader)) {
            return false;
        }

        $payload    = $request->getContent();
        $timestamp  = $request->header('X-Timestamp');

        if (blank($timestamp) || ! ctype_digit($timestamp)) {
            return false;
        }

        // Optional: reject stale timestamps (5 minutes skew)
        if (abs(now()->timestamp - (int) $timestamp) > 300) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        // Some providers send signature as list (e.g., "t=...,sha256=...")
        if (Str::contains($signatureHeader, '=')) {
            $signatures = collect(explode(',', $signatureHeader))
                ->map(fn ($part) => explode('=', $part, 2)[1] ?? null)
                ->filter()
                ->all();

            return in_array($expected, $signatures, true);
        }

        return hash_equals($expected, $signatureHeader);
    }
}
