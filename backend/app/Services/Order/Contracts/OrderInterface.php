<?php

namespace App\Services\Order\Contracts;

interface OrderInterface
{
    public function getOrdersforUser();
    public function getOneOrderforUser($orderId);
}