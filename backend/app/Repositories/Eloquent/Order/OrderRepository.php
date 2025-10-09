<?php

namespace App\Repositories\Eloquent\Order;

use App\Models\Order;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Order\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function getOrdersBySeller($sellerId)
    {
        return $this->model->where('seller_id', $sellerId)->get();
    }

    public function create(array $attributes): Order
    {
        return $this->model->create($attributes);
    }
    public function getOrdersforUser($userId)
    {
        return $this->model->where('user_id', $userId)->orderByDesc('id')->get();
    }

    public function getOrderforUser($orderId, $userId)
    {
        return $this->model->where('user_id', $userId)->where('id', $orderId)->first();
    }
    public function getOrderDetailforUser($userId, $id)
    {
        return $this->model->with('orderItems.product.variants.variantImages.variantSizes.sizeOption', 'orderItems.product.variants.variantImages.variants.variantSizes.inventory')
            ->where('user_id', $userId)
            ->where('id', $id)
            ->orderByDesc('id')
            ->get();
    }

    public function latest(): ?Order
    {
        return $this->model->newQuery()
            ->latest('id')
            ->first();
    }

}