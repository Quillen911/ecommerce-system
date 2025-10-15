<?php

namespace App\Services\Product;

use App\Repositories\Contracts\Product\ProductVariantRepositoryInterface;

class ProductVariantService
{
    
    public function __construct(
        private readonly ProductVariantRepositoryInterface $productVariantRepository,
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
        return $this->productVariantRepository->createVariant($data, $productId);
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
        return $this->productVariantRepository->updateVariant($productId, $id, $data);
    }

    public function destroy($productId, $id)
    {
        return $this->productVariantRepository->deleteVariant($productId, $id);
    }

}