<?php

namespace App\Services\Bag\Contracts;

interface BagInterface
{
    public function getBag();
    public function addToBag($productId, $quantity = 1);
    public function showBagItem($bagItemId);
    public function updateBagItem($bagItemId, $quantity);
    public function destroyBagItem($bagItemId);
}