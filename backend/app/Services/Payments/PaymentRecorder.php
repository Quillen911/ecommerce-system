<?php

namespace App\Services\Payments;

use App\Repositories\Contracts\Payment\PaymentRepositoryInterface;
use App\Models\Order;

class PaymentRecorder
{
    public function __construct(
        private readonly PaymentRepositoryInterface $payments,
    ) {}

    public function record(Order $order, array $paymentData): void
    {
        $this->payments->create([
            'order_id'             => $order->id,
            'provider'             => $paymentData['provider'] ?? null,
            'provider_payment_id'  => $paymentData['intent']['payment_id'] ?? null,
            'amount_cents'         => $paymentData['intent']['amount_cents'] ?? $order->grand_total_cents,
            'status'               => 'authorized',
            'raw_response'         => $paymentData['intent'] ?? [],
        ]);
    }
}
