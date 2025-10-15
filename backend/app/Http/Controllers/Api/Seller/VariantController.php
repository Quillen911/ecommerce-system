<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductVariantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\Product\ProductVariantResource;
use App\Http\Requests\Seller\Product\StoreProductVariantRequest;
use App\Http\Requests\Seller\Product\UpdateProductVariantRequest;

class VariantController extends Controller
{
    protected $variantService;

    public function __construct(ProductVariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    public function index($productId)
    {
        $variants = $this->variantService->index($productId);
        return Response::json(ProductVariantResource::collection($variants));
    }

    public function store(StoreProductVariantRequest $request, $productId)
    {
        $variant = $this->variantService->store($request->all(), $productId);
        return Response::json(new ProductVariantResource($variant));
    }
    public function show($productId, $id)
    {
        $variant = $this->variantService->show($productId, $id);
        return Response::json(new ProductVariantResource($variant));
    }

    public function update(UpdateProductVariantRequest $request, $productId, $id)
    {
        $variant = $this->variantService->update($productId, $id, $request->all());
        return Response::json(new ProductVariantResource($variant));
    }

    public function destroy($productId, $id)
    {
        $variant = $this->variantService->destroy($productId, $id);
        return Response::json($variant);
    }
}