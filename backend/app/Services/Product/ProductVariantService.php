<?php

namespace App\Services\Product;

use Illuminate\Support\Str;
use App\Models\Product;
use App\Repositories\Contracts\Product\ProductVariantRepositoryInterface;
use App\Services\Product\ProductVariantSizeService;

class ProductVariantService
{
    
    public function __construct(
        private readonly ProductVariantRepositoryInterface $productVariantRepository,
        private readonly ProductVariantSizeService $productVariantSizeService,
    ) {

    }

    public function getProductVariantBySlug($slug)
    {
        return $this->productVariantRepository->getProductVariantBySlug($slug);
    }

    public function index($productId)
    {
        return $this->productVariantRepository->getProductVariants($productId);
    }

    public function store($data, $productId)
    {
        $product = Product::findOrFail($productId);
        if(!$product){
            throw new \Exception('Ürün bulunamadı');
        }
        $data['product_id'] = $product->id;
        $data['sku'] = $this->temporary($product, $data['color_name']);
        $data['slug'] = $this->temporary($product, $data['color_name']);
        $variant = $this->productVariantRepository->createVariant($data, $productId);
        $variant->update([
            'sku' => $this->generateSkuForVariant($product, $variant),
            'slug' => $this->generateSlugForVariant($product, $variant),
        ]);
        if((isset($data['sizes']) && $data['sizes'])){
            foreach ($data['sizes'] as $size) {
                $this->createVariantSizeAndInventory($variant, $size);
            }
        }
        return $variant->load('product','variantImages', 'variantSizes.inventory', 'variantSizes.sizeOption');
    }

    public function show($productId,$variantId)
    {
        $variant = $this->productVariantRepository->getProductVariant($productId,$variantId);
        if (!$variant) {
            throw new \Exception('Variant bulunamadı');
        }
        return $variant;
    }

    public function update($productId, $id, $data)
    {
        $product = Product::findOrFail($productId);
        if(!$product){
            throw new \Exception('Ürün bulunamadı');
        }
        
        $variant = $this->productVariantRepository->updateVariant($productId, $id, $data);
        
        $variant->update([
            'sku' => $this->generateSkuForVariant($product, $variant),
            'slug' => $this->generateSlugForVariant($product, $variant),
        ]);
        return $variant->load('product','variantImages', 'variantSizes.inventory', 'variantSizes.sizeOption');
    }

    public function destroy($productId, $id)
    {
        $variant = $this->productVariantRepository->deleteVariant($productId, $id);
        if(!$variant){
            throw new \Exception('Variant bulunamadı');
        }
        return $variant;
    }

    private function temporary($product, $colorName)
    {
        //random
        return $product->slug . '-' . $colorName;
    }

    private function createVariantSizeAndInventory($variant, $size)
    {
        $variantSize = $variant->variantSizes()->create([
            'size_option_id' => $size['size_option_id'],
            'sku' => $this->productVariantSizeService->skuGenerator($variant->id, $size['size_option_id']),
            'price_cents' => $size['price_cents'] ?? $variant->price_cents,
        ]);
        if(isset($size['inventory']) && $size['inventory']){
            foreach ($size['inventory'] as $inv) {
                $variantSize->inventory()->create([
                    'on_hand' => $inv['on_hand'],
                    'reserved'    => $inv['reserved'] ?? 0,
                    'warehouse_id' => $inv['warehouse_id'] ?? 1,
                ]);
            }
        } 
        return $variantSize;
    }
    private function generateSkuForVariant($product, $variant)
    {
        $prefix = strtoupper(Str::slug(substr($product->title, 0, 3)));
        $color  = Str::upper(Str::slug($variant->color_name, 0, 3));
        return "{$prefix}-{$product->id}-{$color}-{$variant->id}";
    }

    private function generateSlugForVariant($product, $variant)
    {
        $productSlug = Str::slug($product->title);
        $colorSlug   = Str::slug($variant->color_name, 0, 3);
        return "{$productSlug}-{$colorSlug}-{$variant->id}";
    }
}