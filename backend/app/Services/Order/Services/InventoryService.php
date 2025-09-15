<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\InventoryInterface;
use App\Exceptions\InsufficientStockException;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;

class InventoryService implements InventoryInterface
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function checkStock($products): bool
    {
        foreach ($products as $product){
            $product = $this->productRepository->getProductWithCategory($product->product_id);

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
            $this->productRepository->decrementStockQuantity($item->product_id, $item->quantity);
            $this->productRepository->incrementSoldQuantity($item->product_id, $item->quantity);
        }
    }

    public function restoreInventory($products): void
    {
        foreach ($products as $item) {
            $this->productRepository->incrementStockQuantity($item->product_id, $item->quantity);
            $this->productRepository->decrementSoldQuantity($item->product_id, $item->quantity);
        }
    }
}