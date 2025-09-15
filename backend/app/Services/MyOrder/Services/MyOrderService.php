<?php

namespace App\Services\MyOrder\Services;

use App\Services\MyOrder\Contracts\MyOrderInterface;
use App\Repositories\Contracts\Order\OrderRepositoryInterface;
use App\Traits\GetUser;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class MyOrderService implements MyOrderInterface
{
    use GetUser;
    protected $orderRepository;
    protected $authenticationRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository, 
        AuthenticationRepositoryInterface $authenticationRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->authenticationRepository = $authenticationRepository;
    }
    public function getOrdersforUser()
    {
        $user = $this->getUser();
        return $this->orderRepository->getOrdersforUser($user->id);
    }

    public function getOneOrderforUser($orderId)
    {
        $user = $this->getUser();
        return $this->orderRepository->getOneOrderforUser($user->id, $orderId);
    }
}