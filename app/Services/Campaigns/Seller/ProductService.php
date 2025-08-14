<?php

namespace App\Services\Campaigns\Seller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;


class ProductService
{

    public function indexProduct($storeId)
    {
        $products = Product::with('category')->where('store_id', $storeId)->orderBy('id')->get();
        return $products;
    }
    public function createProduct(ProductStoreRequest $request)
    {
        $products = Product::create($request->validated());
        return $products;
    }

    public function showProduct($id)
    {
        $products = Product::find($id);
        return $products;
    }

    public function updateProduct(ProductUpdateRequest $request, $id)
    {
        $products = Product::findOrFail($id);
        $products->update($request->validated());
        return $products;
    }

    public function deleteProduct($id)
    {
        $products= Product::findOrFail($id);
        $products->delete();
        return $products;
    }
    public function bulkStoreProduct(Request $request)
    {
        $products = $request->all();
        $created = [];
        
        foreach ($products as $productData) {
            $product = Product::create($productData);
            $created[] = $product;
        }
        return $created;
    }
}
