<?php

namespace App\Services\Order\Contracts;

interface OrderCheckInterface
{
    public function checkOrder($orderId): array;

    public function checkItems($order, array $refundQuantitiesByItemId): array;
}