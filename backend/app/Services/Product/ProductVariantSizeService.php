<?php

namespace App\Services\Product;

use App\Models\ProductVariant;
use App\Models\AttributeOption;
use App\Repositories\Contracts\Inventory\InventoryRepositoryInterface;
use App\Repositories\Contracts\Product\ProductVariantRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductVariantSizeService
{

    public function __construct(
        private readonly InventoryRepositoryInterface $inventoryRepository,
        private readonly ProductVariantRepositoryInterface $productVariantRepository,
    ) {
    }

    public function store($productId,$variantId, $data)
    {
        return DB::transaction(function () use ($productId,$variantId, $data) {
            $variant = $this->productVariantRepository->getProductVariant($productId,$variantId);
            if(!$variant){
                throw new \Exception('Variant bulunamadı');
            }

            $variantSize = $variant->variantSizes()->create([
                'product_variant_id' => $variantId,
                'size_option_id' => $data['size_option_id'],
                'sku' => $this->skuGenerator($variantId, $data['size_option_id']),
                'price_cents' => $data['price_cents'],
                'is_active' => $data['is_active'],
            ]);
            $variantSize->inventory()->create([
                'warehouse_id' => $data['inventory']['warehouse_id'] ?? 1,
                'on_hand' => $data['inventory']['on_hand'],
                'reserved' => $data['inventory']['reserved'] ?? 0,
                'min_stock_level' => $data['inventory']['min_stock_level'] ?? 0,
            ]);
            return $variantSize->load('sizeOption', 'inventory');
        });
    }

    public function update($productId,$variantId, $variantSizeId, $data)
    {
        $variant = $this->productVariantRepository->getProductVariant($productId,$variantId);
        if(!$variant){
            throw new \Exception('Variant bulunamadı');
        }
        $variantSize = $variant->variantSizes()->where('id', $variantSizeId)->first();
        if(!$variantSize){
            throw new \Exception('Variant size bulunamadı');
        }
        $variantSize->update($data);
        $variantSize->update([
            'sku' => $this->skuGenerator($variantId, $data['size_option_id']),
        ]);
        $this->inventoryRepository->updateStock($variantSizeId, $data['inventory']);
        return $variantSize->load('sizeOption', 'inventory');
    }

    public function skuGenerator($variantId, $sizeOptionId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $sizeOption = AttributeOption::findOrFail($sizeOptionId);
        return $variant->sku . '-' . $sizeOption->slug;
    }
}
