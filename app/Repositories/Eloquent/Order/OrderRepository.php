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

}