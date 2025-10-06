<?php

namespace App\Services\Inventory;

use App\Repositories\Contracts\Inventory\InventoryRepositoryInterface;
use Illuminate\Support\Collection;

class InventoryService
{
    public function __construct(
        private readonly InventoryRepositoryInterface $inventories,
    ) {}

    public function decrementForOrderItems(Collection $items): void
    {
        foreach ($items as $item) {
            $this->inventories->decrementStock($item->variant_size_id, $item->quantity);
        }
    }
}
