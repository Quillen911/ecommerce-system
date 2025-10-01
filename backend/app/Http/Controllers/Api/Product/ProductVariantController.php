<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Services\Product\ProductVariantService;
use App\Helpers\ResponseHelper;

class ProductVariantController extends Controller
{
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService = $productVariantService;
    }

    public function variantDetail($variant_slug)
    {
        $variant = $this->productVariantService->getProductVariantBySlug($variant_slug);
        if (!$variant) {
            return ResponseHelper::notFound('Varyant bulunamadı');
        }
        return new ProductResource($variant->product);
    }
}
