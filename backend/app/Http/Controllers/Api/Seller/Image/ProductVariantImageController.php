<?php

namespace App\Http\Controllers\Api\Seller\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Seller\Image\ProductVariantImageService;
use App\Http\Requests\Seller\Product\Image\ProductVariantImageStoreRequest;
use App\Helpers\ResponseHelper;
use App\Http\Resources\Product\ProductVariantImageResource;
use App\Exceptions\AppException;
use App\Http\Requests\Seller\Product\Image\VariantImageReorderRequest;
use App\Models\Product;

class ProductVariantImageController extends Controller
{ 
    protected $productVariantImageService;

    public function __construct(ProductVariantImageService $productVariantImageService)
    {
        $this->productVariantImageService = $productVariantImageService;
    }
    
    public function store(Product $product, ProductVariantImageStoreRequest $request, $id)
    {
        try {
            $image = $this->productVariantImageService->store($request->validated(), $product->slug, $id);
            return ResponseHelper::success('Resim başarıyla oluşturuldu', new ProductVariantImageResource($image));
        } catch (AppException $e) {
            return ResponseHelper::error($e->getMessage(), 403);
        }
    }

    public function destroy(Product $product, $variantId, $id)
    {
        $this->productVariantImageService->destroy($product->slug, $variantId, $id);
        return ResponseHelper::success('Resim başarıyla silindi');
    }
    
    public function reorder(Product $product, $variantId, VariantImageReorderRequest $request)
    {
        try {
            $data = $request->validated()['images'];
            $this->productVariantImageService->reorder($data, $product->slug, $variantId);
            return ResponseHelper::success('Resimler başarıyla sıralandı');
        } catch (AppException $e) {
            return ResponseHelper::error($e->getMessage(), 403);
        }
    }
}
