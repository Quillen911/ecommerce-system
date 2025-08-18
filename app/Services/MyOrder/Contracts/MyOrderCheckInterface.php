<?php

namespace App\Services\MyOrder\Contracts;

interface MyOrderCheckInterface
{
    public function checkOrder($userId, $orderId): array;

    public function checkItems($order, array $refundQuantitiesByItemId): array;
}