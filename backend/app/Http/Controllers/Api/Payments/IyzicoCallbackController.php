<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class IyzicoCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::debug('iyzico.callback', $request->all());

        Http::asForm()
            ->withHeaders([
                'User-Agent' => 'curl/7.88.1',
                'ngrok-skip-browser-warning' => 'true',
            ])
            ->timeout(10)
            ->post(config('app.url') . '/api/checkout/confirm', $request->all());

        $sessionId = $this->extractSessionId($request->input('conversationId'));
        $redirectUrl = $this->buildSuccessUrl($sessionId);

        if ($this->isBrowser($request->header('User-Agent'))) {
            Log::info('iyzico.callback.redirect', ['url' => $redirectUrl]);
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

    private function buildSuccessUrl(?string $sessionId): string
    {
        $base = rtrim(config('services.frontend_url'), '/')
            ?: 'http://localhost:3000';

        $url = $base . '/checkout/success';

        return $sessionId ? "{$url}?session={$sessionId}" : $url;
    }

    private function isBrowser(?string $userAgent): bool
    {
        return str_contains($userAgent ?? '', 'Mozilla');
    }
}
