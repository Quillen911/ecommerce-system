<?php

namespace App\Services\Order\Contracts\Refund;

interface OrderRefundInterface
{
    public function createRefund($order, array $payload);
    public function handleShipmentWebhook(array $payload);
    public function handlePaymentWebhook(array $payload);
}