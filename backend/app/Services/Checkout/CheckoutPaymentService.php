<?php

namespace App\Services\Checkout;

use App\Models\PaymentMethod;
use App\Models\PaymentProvider;
use App\Models\User;
use App\Models\CheckoutSession;

use App\Repositories\Contracts\Payment\PaymentProviderRepositoryInterface;
use App\Services\Payments\Contracts\PaymentGatewayInterface;

class CheckoutPaymentService
{
    public function __construct(
        private readonly PaymentProviderRepositoryInterface $paymentProviders,
    ) {
    }
    public function buildTemporaryMethodFromData($user, array $data): PaymentMethod
    {
        $provider = $this->resolveProvider($data['provider'] ?? null);

        return app(PaymentGatewayInterface::class, ['provider' => $provider])
            ->buildTemporaryMethod($user, $data);
    }

    public function createPaymentIntent(User $user, $session, $paymentMethod, array $data): array
    {
        $provider = $this->resolveProvider($paymentMethod->provider);  

        $gateway = app(PaymentGatewayInterface::class, ['provider' => $provider]);

        return $gateway->processPayment($user, $session, $paymentMethod, $data);
    }

    public function confirmPaymentIntent(CheckoutSession $session, array $payload): array
    {
        $provider = $this->resolveProvider($session->payment_data['provider'] ?? null);

        $gateway = app(PaymentGatewayInterface::class, ['provider' => $provider]);

        return $gateway->confirmPayment($session, $payload);
    }

    private function resolveProvider(?string $code): PaymentProvider
    {
        $provider = $this->paymentProviders->findActiveByCode($code);
        if (! $provider) {
            throw new \RuntimeException('Aktif ödeme sağlayıcısı bulunamadı.');
        }
        return $provider;
    }
}