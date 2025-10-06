<?php

namespace App\Repositories\Eloquent\Inventory;

use App\Models\Inventory;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Inventory\InventoryRepositoryInterface;

class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    public function __construct(Inventory $model)
    {
        $this->model = $model;
    }

    public function lockForUpdate(int|string $id)
    {
        return $this->model->newQuery()->lockForUpdate()->findOrFail($id);
    }

    public function decrementStock(int $variantSizeId, int $quantity): void
    {
        $inventory = $this->model->where('variant_size_id', $variantSizeId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($inventory->available < $quantity) {
            throw new \RuntimeException('Yetersiz stok');
        }

        $inventory->decrement('on_hand', $quantity);
        $inventory->decrement('available', $quantity);
    }
}