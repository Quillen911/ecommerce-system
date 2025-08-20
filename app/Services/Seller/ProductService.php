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
    public function indexProduct($storeId)
    {
        return $this->productRepository->getProductsByStore($storeId);
    }
    
    public function createProduct(ProductStoreRequest $request)
    {
        $seller = auth('seller_web')->user();
        $store = $this->storeRepository->getStoreBySellerId($seller->id);

        $productData = $request->all();
        $productData['store_id'] = $store->id;
        $productData['store_name'] = $store->name;
        $productData['sold_quantity'] = 0;
        
        if($request->hasFile('images')){
            $productData['images'] = $request->file('images');
        }
        
        return $this->productRepository->createProduct($productData);
    }

    public function showProduct($id)
    {
        return $this->productRepository->findProductById($id);
    }

    public function updateProduct(ProductUpdateRequest $request, $id)
    {
        return $this->productRepository->updateProduct($request->validated(), $id);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->deleteProduct($id);
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
