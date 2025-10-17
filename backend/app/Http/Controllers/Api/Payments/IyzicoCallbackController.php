<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\ConfirmOrderRequest;
use App\Models\User;
use App\Services\Checkout\CheckoutSessionService;
use App\Services\Checkout\Orders\OrderPlacementService;
use Illuminate\Http\Request;
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

        $rules = (new ConfirmOrderRequest())->rules();
        Validator::make($payload, $rules)->validate();

        try {
            $session = $checkoutSessions->confirmPaymentIntent($payload);

            if ($session->status === 'confirmed') {
                $user = $session->user ?: User::find($session->user_id);
                if ($user) {
                    $orderPlacement->placeFromSession($user, $session, $payload);
                }
            }
        } catch (\Throwable $e) {
        }

        $sessionId = $this->extractSessionId($payload['conversationId'] ?? null);

        if ($this->isBrowser($request->header('User-Agent'))) {
            $redirectUrl = $this->buildFrontendUrl($sessionId);
            return Redirect::away($redirectUrl);
        }

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
