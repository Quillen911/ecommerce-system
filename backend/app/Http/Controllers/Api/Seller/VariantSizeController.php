<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Product\StoreVariantSizeRequest;
use App\Http\Requests\Seller\Product\UpdateVariantSizeRequest;
use App\Http\Resources\Product\VariantSizeResource;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantSize;
use App\Services\Product\ProductVariantSizeService;
use Illuminate\Support\Facades\Response;

class VariantSizeController extends Controller
{
    public function __construct(
        private readonly ProductVariantSizeService $variantSizeService,
    ) {}

    public function store(StoreVariantSizeRequest $request, Product $product, ProductVariant $variant)
    {
        $variantSize = $this->variantSizeService->store(
            $product->id,
            $variant->id,
            $request->validated()
        );

        return Response::json(
            new VariantSizeResource($variantSize)
        );
    }

    public function update(
        UpdateVariantSizeRequest $request,
        Product $product,
        ProductVariant $variant,
        VariantSize $size
    ) {
        $variantSize = $this->variantSizeService->update(
            $product->id,
            $variant->id,
            $size->id,
            $request->validated()
        );

        return Response::json(
            new VariantSizeResource($variantSize)
        );
    }

    public function destroy(Product $product, ProductVariant $variant, VariantSize $size)
    {
        $this->variantSizeService->destroy(
            $product->id,
            $variant->id,
            $size->id
        );

        return Response::json([
            'message' => 'Variant başarıyla silindi',
        ]);
    }
}
