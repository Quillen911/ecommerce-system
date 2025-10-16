<?php

namespace App\Observers;

use App\Models\Inventory;

class InventoryObserver
{
    public function saving(Inventory $inventory): void
    {
        $onHand   = (int) ($inventory->on_hand ?? 0);
        $reserved = (int) ($inventory->reserved ?? 0);

        $inventory->available = max(0, $onHand - $reserved);
    }
}
