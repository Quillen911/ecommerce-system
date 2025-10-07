<?php

namespace App\Repositories\Contracts\OrderItem;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Models\OrderItem;
use Illuminate\Support\Collection;

interface OrderItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getOrderItemsBySeller($storeId);
    public function getOrderItemBySeller($storeId, $id);
    public function getOrderItemById($storeId, $id);
    public function getOrderDetailforUser($userId, $orderId);
    public function create(array $attributes): OrderItem;
    public function createMany(array $items): Collection;
}
