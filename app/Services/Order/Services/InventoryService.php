<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\InventoryInterface;
use App\Exceptions\InsufficientStockException;
use App\Models\Product;

class InventoryService implements InventoryInterface
{
    public function checkStock($products): bool
    {
        foreach ($products as $product){
            $product = Product::find($product->product_id);

            if(!$product || $product->stock_quantity < $product->quantity){
                throw new InsufficientStockException(
                    " '{$product->title}' ürünü için yeterli stok yok. Stok: {$product->stock_quantity}, İstenen: {$item->quantity}"
                );
            }
        }
        return true;    
    }

    public function updateInventory($products): void
    {
        foreach ($products as $item) {
            Product::whereKey($item->product_id)->decrement('stock_quantity', $item->quantity);
            Product::whereKey($item->product_id)->increment('sold_quantity', $item->quantity);
        }
    }

    public function restoreInventory($products): void
    {
        foreach ($products as $item) {
            Product::whereKey($item->product_id)->increment('stock_quantity', $item->quantity);
            Product::whereKey($item->product_id)->decrement('sold_quantity', $item->quantity);
        }
    }
}