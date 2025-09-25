<?php

namespace App\Services\Seller\Image;

use App\Repositories\Contracts\Image\ProductImageRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    protected $productImageRepository;
    protected $authenticationRepository;
    protected $productRepository;
    public function __construct(
        ProductImageRepositoryInterface $productImageRepository, 
        AuthenticationRepositoryInterface $authenticationRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productImageRepository = $productImageRepository;
        $this->authenticationRepository = $authenticationRepository;
        $this->productRepository = $productRepository;
    }

    public function store(array $data, $productSlug)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new AppException('Satıcı bulunamadı');
        }

        $product = $this->productRepository->getProductBySlugAndStore($seller->store->id, $productSlug);
        if(!$product){
            throw new AppException('Ürün bulunamadı veya bu ürüne erişim yetkiniz yok');
        }

        return $this->productImageRepository->store($data, $product->id);
    }

    public function destroy($productSlug, $id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new AppException('Satıcı bulunamadı');
        }
        $product = $this->productRepository->getProductBySlugAndStore($seller->store->id, $productSlug);
        if(!$product){
            throw new AppException('Ürün bulunamadı veya bu ürüne erişim yetkiniz yok');
        }
        $image = $this->productImageRepository->getImageByProductIdAndId($product->id, $id);
        if(!$image){
            throw new AppException('Resim bulunamadı');
        }
        Storage::disk('public')->delete('productImages/' . $image->image);
        $image->delete();
        return true;
    }

    public function reorder($data, $productSlug)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new AppException('Satıcı bulunamadı');
        }
        $product = $this->productRepository->getProductBySlugAndStore($seller->store->id, $productSlug);
        if(!$product){
            throw new AppException('Ürün bulunamadı veya bu ürüne erişim yetkiniz yok');
        }
        $images = $this->productImageRepository->updateImageOrders($product->id, $data);
        return true;
    }
}
