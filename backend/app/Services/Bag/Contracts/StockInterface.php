<?php

namespace App\Services\Bag\Contracts;

interface StockInterface
{
    public function checkStockAvailability($bag, $productId, $quantity = 1);
    public function reserveStock($bag, $productId, $quantity = 1);
}