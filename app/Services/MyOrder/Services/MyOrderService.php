<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Repositories\Contracts\Order\OrderRepositoryInterface;

class MyOrderService implements MyOrderInterface
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    public function getOrdersforUser($userId)
    {
        return $this->orderRepository->getOrdersforUser($userId);
    }

    public function getOneOrderforUser($userId, $orderId)
    {
        return $this->orderRepository->getOneOrderforUser($userId, $orderId);
    }
}