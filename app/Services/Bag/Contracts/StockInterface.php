<?php

namespace App\Services\Bag\Contracts;

interface StockInterface
{
    public function checkStockAvailability($bag, $productId);
    public function reserveStock($bag, $productId);
}