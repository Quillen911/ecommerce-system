<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductVariantResource;
use App\Http\Resources\Product\ProductVariantSummaryResource;
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
        $similarProducts = $variant->product->similarProducts()
            ->with('category')
            ->with('category.parent')
            ->with('category.gender')
            ->with('variants')
            ->with('variants.variantImages')
            ->with('variants.variantSizes.sizeOption')
            ->with('variants.variantSizes.inventory')
            ->get();
        
        $product = $variant->product->load(
            'category',
            'category.parent',
            'category.gender'
        );
    
        $selectedVariant = $variant->load(
            'variantImages',
            'variantSizes.sizeOption',
            'variantSizes.inventory'    
        );

        $allVariants = $product->variants()
            ->where('product_id', $variant->product_id)
            ->with('variantImages')
            ->with('variantSizes.sizeOption')
            ->with('variantSizes.inventory')
            ->get();
        
        return response()->json([
            'data' => new ProductResource($product->setRelation('variants', collect([$selectedVariant]))),
            'selected_variant_id' => $variant->id,
            'all_variants' => ProductVariantSummaryResource::collection($allVariants),
            'similar_products' => ProductResource::collection($similarProducts) ?? null,
        ]);
    }
    
}
