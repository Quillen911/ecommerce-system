<?php

namespace App\Services\Product;

use App\Repositories\Contracts\Product\ProductVariantRepositoryInterface;

class ProductVariantService
{
    protected $productVariantRepository;

    public function __construct(ProductVariantRepositoryInterface $productVariantRepository)
    {
        $this->productVariantRepository = $productVariantRepository;
    }

    public function getProductVariantBySlug($slug)
    {
        return $this->productVariantRepository->getProductVariantBySlug($slug);
    }
}