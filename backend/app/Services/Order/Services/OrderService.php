<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\OrderInterface;
use App\Repositories\Contracts\Order\OrderRepositoryInterface;
use App\Repositories\Contracts\OrderItem\OrderItemRepositoryInterface;
use App\Traits\GetUser;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;

class OrderService implements OrderInterface
{
    use GetUser;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository, 
        private readonly OrderItemRepositoryInterface $orderItemRepository, 
        private readonly AuthenticationRepositoryInterface $authenticationRepository
    ) {
    }
    public function getOrdersforUser()
    {
        $user = $this->getUser();
        $orders = $this->orderRepository->getOrdersforUser($user->id);
        if(!$orders){
            throw new \Exception('Sipariş bulunamadı.');
        }
        return $orders;
    }

    public function getOneOrderforUser($orderId)
    {
        $user = $this->getUser();
        $orders = $this->orderItemRepository->getOrderDetailforUser($user->id, $orderId);
        if(!$orders || $orders->isEmpty()){
            throw new \Exception('Sipariş bulunamadı.');
        }
        return $orders;
    }
}