<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\ConfirmOrderRequest;
use App\Models\User;
use App\Services\Checkout\CheckoutSessionService;
use App\Services\Checkout\Orders\OrderPlacementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class IyzicoCallbackController extends Controller
{
    public function __invoke(
        Request $request,
        CheckoutSessionService $checkoutSessions,
        OrderPlacementService $orderPlacement
    ) {
        $payload = $request->all();
        Log::debug('iyzico.callback', $payload);

        // 1) callback verisini ConfirmOrderRequest kurallarına göre doğrula
        $rules = (new ConfirmOrderRequest())->rules();
        Validator::make($payload, $rules)->validate();

        try {
            // 2) 3D işlemini finalize et
            $session = $checkoutSessions->confirmPaymentIntent($payload);

            if ($session->status === 'confirmed') {
                $user = $session->user ?: User::find($session->user_id);
                if ($user) {
                    $orderPlacement->placeFromSession($user, $session, $payload);
                }
            }
        } catch (\Throwable $e) {
            Log::error('iyzico.callback.error', ['message' => $e->getMessage()]);
        }

        // 3) session id’yi conversationId’den çıkar
        $sessionId = $this->extractSessionId($payload['conversationId'] ?? null);

        // 4) tarayıcıdan gelen istekse frontend’e yönlendir
        if ($this->isBrowser($request->header('User-Agent'))) {
            $redirectUrl = $this->buildFrontendUrl($sessionId);
            Log::info('iyzico.callback.redirect', ['url' => $redirectUrl]);
            return Redirect::away($redirectUrl);
        }

        // 5) Iyzipay sistem çağrısına JSON onay dön
        return response()->json(['received' => true]);
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

    private function isBrowser(?string $userAgent): bool
    {
        return str_contains($userAgent ?? '', 'Mozilla');
    }
}
