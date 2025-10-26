<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Checkout\CheckoutSessionService;
use App\Jobs\OrderPlacementJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\CheckoutSession;

class IyzicoCallbackController extends Controller
{
    
    public function __invoke(Request $request, CheckoutSessionService $checkoutSessions)
    {
        $payload = $request->all();

        if (empty($payload['conversationId'])) {
            Log::warning('Iyzico callback: Missing conversationId', $payload);
            return response()->json(['error' => 'conversationId missing'], 400);
        }

        $sessionId = $this->extractSessionId($payload['conversationId'] ?? null);

        Log::info('Iyzico callback', ['payload' => $payload]);

        if (($payload['mdStatus'] ?? '') === "0" || ($payload['status'] ?? '') === "failure") {
            Log::info('Iyzico callback: Payment failed', ['payload' => $payload]);

            if ($this->isBrowser($request->header('User-Agent'))) {
                return Redirect::away($this->buildFailedFrontendUrl($sessionId));
            }

            return response()->json(['status' => 'failed', 'reason' => '3D verification failed'], 200);
        }

        $session = null;

        try {
            $session = $checkoutSessions->confirmPaymentIntent($payload);
            Log::info('Session', ['payload' => $payload, 'session' => $session]);

            if (($payload['mdStatus'] ?? '') === "1") {
                $user = $session['user'] ?? User::find($session['user_id']);
                if ($user) {
                    OrderPlacementJob::dispatch($user, $session, $payload);
                }
            }

            if ($this->isBrowser($request->header('User-Agent'))) {
                return Redirect::away($this->buildFrontendUrl($sessionId));
            }

            return response()->json(['received' => true]);
        } catch (\Throwable $e) {
            $gatewayMessage = $payload['errorMessage']
                ?? $payload['localeMessage']
                ?? $payload['message']
                ?? $e->getMessage();

            Log::error('Iyzico callback error', [
                'message' => $gatewayMessage,
                'payload' => $payload,
            ]);

            if ($session instanceof CheckoutSession) {
                $session->update(['status' => 'pending_3ds']);
            }

            if ($this->isBrowser($request->header('User-Agent'))) {
                $redirect = $this->buildFailedFrontendUrl($sessionId);
                return Redirect::away($redirect . (str_contains($redirect, '?') ? '&' : '?') . 'error=' . urlencode($gatewayMessage));
            }

            return response()->json([
                'status'  => 'failure',
                'message' => $gatewayMessage,
            ], 402);
        }
    }
    private function extractSessionId(?string $conversationId): ?string
    {
        if (!$conversationId) {
            return null;
        }

        $matched = Str::match('/^session_([0-9a-f\-]+)/i', $conversationId);
        return $matched ?: null;
    }

    private function buildFrontendUrl(?string $sessionId): string
    {
        $base = rtrim(config('services.frontend_url'), '/') ?: 'http://localhost:3000';
        $url = $base . '/checkout/success';
        return $sessionId ? "{$url}?session={$sessionId}" : $url;
    }
    private function buildFailedFrontendUrl(?string $sessionId): string
    {
        $base = rtrim(config('services.frontend_url'), '/') ?: 'http://localhost:3000';
        $url = $base . '/checkout/payment';
        return $sessionId ? "{$url}?session={$sessionId}" : $url;
    }

    private function isBrowser(?string $userAgent): bool
    {
        return str_contains($userAgent ?? '', 'Mozilla');
    }
}
