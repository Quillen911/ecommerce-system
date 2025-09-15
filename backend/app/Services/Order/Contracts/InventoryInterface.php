<?php

namespace App\Services\Order\Contracts;

interface InventoryInterface
{
    public function updateInventory($products): void;

    public function restoreInventory($products): void;

    public function checkStock($products): bool;
}
