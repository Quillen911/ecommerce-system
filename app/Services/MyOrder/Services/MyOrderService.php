<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderInterface;

use App\Models\Order;

class MyOrderService implements MyOrderInterface
{
    public function getOrdersforUser($userId)
    {
        return Order::with('orderItems.product.category')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();
    }

    public function getOneOrderforUser($userId, $orderId)
    {
        return Order::with('orderItems.product.category')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }
}