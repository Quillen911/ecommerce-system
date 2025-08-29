<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\CreditCard;

interface PaymentInterface 
{
    public function processPayment(Order $order, CreditCard $creditCard, float $amount, array $tempCardData = null): array;

    public function checkPaymentStatus(string $paymentId): array;

    //public function cancelPayment(string $paymentId): array;
    
    public function refundPayment(string $paymentTransactionId, float $amount): array;
    
    public function createCardToken(array $cardData, $userId): array;
}