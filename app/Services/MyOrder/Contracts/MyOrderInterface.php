<?php

namespace App\Services\MyOrder\Contracts;

interface MyOrderInterface
{
    public function getOrdersforUser($userId);
    public function getOneOrderforUser($userId, $orderId);
}