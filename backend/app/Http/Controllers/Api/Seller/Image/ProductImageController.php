<?php

namespace App\Http\Controllers\Api\Seller\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Product\Image\ProductImageStoreRequest;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Seller\Image\ProductImageService;
use App\Models\Product;
use App\Exceptions\AppException;
use App\Http\Resources\Product\ProductImageResource;

class ProductImageController extends Controller
{
    protected $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->productImageService = $productImageService;
    }

    public function store(ProductImageStoreRequest $request, Product $product)
    {
        try {
            $image = $this->productImageService->store($request->validated(), $product->slug);
            return ResponseHelper::success('Resim başarıyla oluşturuldu', new ProductImageResource($image));
        } catch (AppException $e) {
            return ResponseHelper::error($e->getMessage(), 403);
        }
    }
}
 