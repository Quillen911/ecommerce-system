<?php

namespace App\Repositories\Eloquent\OrderItem;

use App\Models\OrderItem;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    public function __construct(OrderItem $model)
    {
        $this->model = $model;
    }

    public function getOrderItemsBySeller($storeId)
    {
        return $this->model->with(['product'])->where('store_id', $storeId)->orderBy('created_at', 'desc')->get();
    }

    public function getOrderItemBySeller($storeId, $id)
    {
        return $this->model->with(['product'])->where('store_id', $storeId)->where('order_id', $id)->first();
    }

    public function getOrderItemById($storeId, $id)
    {
        return $this->model->with(['product'])->where('store_id', $storeId)->where('id', $id)->first();
    }
    public function getOrderItemByOrderId($itemId, $orderId, $userId)
    {
        return $this->model->where('id', $itemId)
                        ->where('order_id', $orderId)
                        ->whereHas('order', function($query) use ($userId) {
                            $query->where('user_id', $userId);
                        })
                        ->get();
    }
}