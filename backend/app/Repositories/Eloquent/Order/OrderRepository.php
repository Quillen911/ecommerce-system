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
    public function getOrdersforUser(int|string $id, $userId): Order
    {
        return $this->model->with('orderItems.product.category')->where('user_id', $userId)->orderByDesc('id')->get();
    }

    public function latest(): ?Order
    {
        return $this->model->newQuery()
            ->latest('id')
            ->first();
    }

}