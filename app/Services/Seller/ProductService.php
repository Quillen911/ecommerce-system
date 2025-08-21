<?php

namespace App\Services\Seller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Models\Category;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;

class ProductService
{
    protected $productRepository;
    protected $categoryRepository;
    protected $storeRepository;
    public function __construct(
        ProductRepositoryInterface $productRepository, 
        CategoryRepositoryInterface $categoryRepository, 
        StoreRepositoryInterface $storeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
    }
    public function indexProduct($sellerId)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);

        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }

        return $this->productRepository->getProductsByStore($store->id);
    }
    
    public function createProduct($sellerId, array $request)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);

        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $productData = array_merge($request, [
            'store_id' => $store->id,
            'store_name' => $store->name,
            'sold_quantity' => 0,
        ]);
        if(isset($request['images'])){
            $productData['images'] = $request['images'];
        }
        return $this->productRepository->createProduct($productData);
    }

    public function showProduct($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->productRepository->getProductByStore($store->id, $id);
    }

    public function updateProduct($sellerId, array $request, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->productRepository->updateProduct($request, $store->id, $id);
    }

    public function deleteProduct($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->productRepository->deleteProduct($store->id, $id);
    }
    
    public function bulkStoreProduct(Request $request)
    {
        return $this->productRepository->bulkCreateProducts($request->all());
    }
    
    public function getCategories()
    {
        return $this->categoryRepository->all();
    }
}
