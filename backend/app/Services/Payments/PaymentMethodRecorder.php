<?php

namespace App\Services\Payments;

use App\Models\User;
use App\Models\PaymentMethod;
use App\Repositories\Contracts\Payment\PaymentMethodRepositoryInterface;
use App\Services\Payments\Contracts\PaymentGatewayInterface;
use App\Repositories\Contracts\Payment\PaymentProviderRepositoryInterface;

class PaymentMethodRecorder
{
    public function __construct(
        private readonly PaymentMethodRepositoryInterface $paymentMethods,
        private readonly PaymentProviderRepositoryInterface $paymentProviders,

    ) {}

    public function store(User $user, array $payload): PaymentMethod
    {
        if (!($payload['save_card'] ?? false)) {
            return new PaymentMethod();
        }
        $provider = $this->paymentProviders->findActiveByCode($payload['provider']);
        $method = $this->prepareMethod($user, $payload);
        
        $stored = app(PaymentGatewayInterface::class, ['provider' => $provider])
            ->storePaymentMethod($user, $method, $payload['new_card_payload']);

        return $this->paymentMethods->saveFromGateway($stored);
    }

    private function prepareMethod(User $user, array $payload): PaymentMethod
    {
        $raw = json_decode($payload['intent']['raw'], true);
        
        return new PaymentMethod([
            'user_id'          => $user->id,
            'provider'         => $payload['provider'],
            'type'             => 'card',
            'brand'            => $raw['cardAssociation'] ?? null,
            'last4'            => substr($payload['new_card_payload']['card_number'] ?? '', -4),
            'metadata' => [
                'card_family' => $raw['cardFamily'] ?? null,
                'card_type'   => $raw['cardType'] ?? null,
            ],
            'is_active' => true,
        ]);
    }
}
