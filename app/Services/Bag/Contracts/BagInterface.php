<?php

namespace App\Services\Bag\Contracts;

interface BagInterface
{
    public function getBag();
    public function addToBag($bag, $productId);
    public function showBagItem($bag, $bagItemId);
    public function updateBagItem($bag, $bagItemId, $quantity);
    public function destroyBagItem($bag, $bagItemId);
}