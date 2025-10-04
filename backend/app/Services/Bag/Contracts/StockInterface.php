<?php

namespace App\Services\Bag\Contracts;

interface StockInterface
{
    public function checkStockAvailability($bag, $variantSizeId, $quantity = 1);
    public function reserveStock($itemInTheBag, $stock, $bag, $variantSizeId, $quantity = 1);
}