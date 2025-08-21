<?php

namespace App\Repositories\Contracts\Product;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductsWithCategory($perPage = 100);
    public function getProductWithCategory($id);

    public function getProductsByStore($storeId);

    public function createProduct(array $productData);
    public function updateProduct(array $productData, $storeId, $id);
    public function deleteProduct($storeId, $id);
    public function bulkCreateProducts(array $productsData);

    public function getProductByStore($storeId, $id);
}
