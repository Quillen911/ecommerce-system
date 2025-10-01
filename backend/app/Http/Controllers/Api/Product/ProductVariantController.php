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
        $product = $variant->product->load(
            'category',
            'category.parent'
        );
    
        $product->setRelation('variants', collect([
            $variant->load(
                'variantImages',
                'variantAttributes.attribute',
                'variantAttributes.option'
            )
        ]));
    
        return new ProductResource($product);
    }
}
