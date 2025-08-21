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
    public function getUserOrderById($userId, $id)
    {
        return $this->model->where('user_id', $userId)->find($id);
    }

    public function getOrdersBySeller($sellerId)
    {
        return $this->model->where('seller_id', $sellerId)->get();
    }
    public function getOrdersforUser($userId)
    {
        return $this->model->with('orderItems.product.category')->where('user_id', $userId)->orderByDesc('id')->get();
    }

    public function getOneOrderforUser($userId, $id)
    {
        return $this->model->with('orderItems.product.category')->where('user_id', $userId)->where('id', $id)->first();
    }

}