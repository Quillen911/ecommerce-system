<?php

namespace App\Repositories\Contracts\OrderItem;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface OrderItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getOrderItemsBySeller($storeId);
    public function getOrderItemBySeller($storeId, $id);
    public function getOrderItemById($storeId, $id);
    public function getOrderItemByOrderId($productId, $orderId, $userId);
}
