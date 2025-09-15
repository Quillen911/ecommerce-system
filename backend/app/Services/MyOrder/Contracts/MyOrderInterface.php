<?php

namespace App\Services\MyOrder\Contracts;

interface MyOrderInterface
{
    public function getOrdersforUser();
    public function getOneOrderforUser($orderId);
}