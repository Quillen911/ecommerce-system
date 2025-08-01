<?php

namespace App\Services\Campaigns\Admin;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;

class ProductService
{

    public function indexProduct()
    {
        $products = Product::orderBy('id')->get();
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
}