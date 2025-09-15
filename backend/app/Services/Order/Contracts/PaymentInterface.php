<?php

namespace App\Services\Order\Contracts;

use App\Models\Order;
use App\Models\CreditCard;

interface PaymentInterface
{
    public function processPayment(Order $order, CreditCard $creditCard, float $amount, array $tempCardData = null): array;

    public function handlePaymentSuccess(Order $order, array $paymentResult): void;

    public function handlePaymentFailed(Order $order, string $error, ?string $errorCode = null): void;
}
