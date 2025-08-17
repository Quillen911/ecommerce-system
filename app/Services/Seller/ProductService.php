<?php

namespace App\Services\Seller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Models\Store;
use App\Models\Category;


class ProductService
{

    public function indexProduct($storeId)
    {
        $products = Product::with('category')->where('store_id', $storeId)->orderBy('id')->get();
        return $products;
    }
    public function createProduct(ProductStoreRequest $request)
    {
        $seller = auth('seller_web')->user();
        $store = Store::where('seller_id', $seller->id)->first();
        $productData = $request->all();
        $productData['store_id'] = $store->id;
        $productData['store_name'] = $store->name;
        $productData['sold_quantity'] = 0;
        if($request->hasFile('images')){
            $images = [];
            foreach($request->file('images') as $image){
                $filename = time() . '.' . $image->getClientOriginalName();
                $image->storeAs('productsImages', $filename, 'public');
                $images[] = $filename;
            }
            $productData['images'] = $images;
            
        }
        $products = Product::create($productData);
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
    public function getCategories()
    {
        return Category::where('category_title')->get();
    }
}
