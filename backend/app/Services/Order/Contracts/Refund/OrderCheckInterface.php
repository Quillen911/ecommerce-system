<?php

namespace App\Services\Order\Contracts\Refund;

interface OrderCheckInterface
{
    public function checkOrder($orderId, $userId);

    public function checkItems($order, array $payloadItems): array;

    public function checkItem($order, $payloadItem): bool;
}