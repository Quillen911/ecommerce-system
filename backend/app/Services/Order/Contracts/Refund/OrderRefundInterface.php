<?php

namespace App\Services\Order\Contracts\Refund;

interface OrderRefundInterface
{
    public function createRefund($order, array $payload);
    public function markShipping($order, $refund, array $payload);
    public function markReceived($order, $refund, array $payload);
    public function markCompleted($order, $refund,array $payload);
    public function markRejected($order, $refund,array $payload);
}