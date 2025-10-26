<?php

namespace App\Repositories\Contracts\Product;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    // Ürün + kategori ilişkileri
    public function getProductsWithCategory($perPage = 100);
    public function getProductWithCategory($id);
    public function getProductsByStore($storeId);
    public function getProductByStore($storeId, $id);
    public function getProductBySlug($storeId, $slug);

    // CRUD
    public function createProduct(array $productData);
    public function updateProduct(array $productData, $storeId, $id);
    public function deleteProduct($storeId, $id);
    public function bulkCreateProducts(array $productsData);

    // Stok ve satış işlemleri
    public function incrementStockQuantity($productId, $quantity);
    public function decrementStockQuantity($productId, $quantity);
    public function incrementSoldQuantity($productId, $quantity);
    public function decrementSoldQuantity($productId, $quantity);
    public function incrementTotalSoldQuantity($productId, $quantity);
    public function decrementTotalSoldQuantity($productId, $quantity);
}
