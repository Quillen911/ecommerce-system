<?php

namespace App\Services\Seller\Image;

use App\Repositories\Contracts\Image\ProductVariantImageRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Repositories\Contracts\Product\ProductVariantRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Storage;

class ProductVariantImageService
{
    protected $productVariantImageRepository;
    protected $authenticationRepository;
    protected $productVariantRepository;
    protected $productRepository;
    public function __construct(
        ProductVariantImageRepositoryInterface $productVariantImageRepository,
        AuthenticationRepositoryInterface $authenticationRepository,
        ProductVariantRepositoryInterface $productVariantRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productVariantImageRepository = $productVariantImageRepository;
        $this->authenticationRepository = $authenticationRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->productRepository = $productRepository;
    }

    public function store(array $data, $productId, $productVariantId)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new AppException('Satıcı bulunamadı');
        }
        $product = $this->productRepository->getProductByStore($seller->store->id, $productId);
        if(!$product){
            throw new AppException('Ürün bulunamadı veya bu ürüne erişim yetkiniz yok');
        }
        $productVariant = $this->productVariantRepository->getProductVariantById($productVariantId);
        if(!$productVariant){
            throw new AppException('Ürün varyantı bulunamadı');
        }
        return $this->productVariantImageRepository->store($data, $productVariant->id);
    }

    public function destroy($productId, $productVariantId, $id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new AppException('Satıcı bulunamadı');
        }
        $product = $this->productRepository->getProductByStore($seller->store->id, $productId);
        if(!$product){
            throw new AppException('Ürün bulunamadı veya bu ürüne erişim yetkiniz yok');
        }
        $productVariant = $this->productVariantRepository->getProductVariantById($productVariantId);
        if(!$productVariant){
            throw new AppException('Ürün varyantı bulunamadı');
        }
        $image = $this->productVariantImageRepository->getImageByProductVariantIdAndId($productVariant->id, $id);
        
        if(!$image){
            throw new AppException('Resim bulunamadı');
        }
        $image->delete();
        Storage::disk('public')->delete('productImages/' . $image->image);
        return true;
    }

    public function reorder($data, $productId, $productVariantId)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new AppException('Satıcı bulunamadı');
        }
        $product = $this->productRepository->getProductByStore($seller->store->id, $productId);
        if(!$product){
            throw new AppException('Ürün bulunamadı veya bu ürüne erişim yetkiniz yok');
        }
        $productVariant = $this->productVariantRepository->getProductVariantById($productVariantId);
        if(!$productVariant){
            throw new AppException('Ürün varyantı bulunamadı');
        }
        $images = $this->productVariantImageRepository->updateImageOrders($productVariant->id, $data);
        return true;
    }
}