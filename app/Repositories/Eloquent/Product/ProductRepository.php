<?php

namespace App\Repositories\Eloquent\Product;

use App\Models\Product;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        $this->model = $model;
    }
    public function getProductsWithCategory($perpage = 100)
    {
        $page = request('page', 1);
        return Cache::remember("products.page.$page", 60, function () use ($perpage) {
            return $this->model->with(['category'])->orderBy('id')->paginate($perpage);
        });
    }
    
    public function getProductWithCategory($id)
    {
        return $this->model->with(['category'])->find($id);
    }

    public function getProductsByStore($storeId)
    {
        return $this->model->with(['category'])->where('store_id', $storeId)->orderBy('id')->get();
    }

    public function createProduct(array $productData)
    {
        if (isset($productData['list_price'])) {
            $productData['list_price_cents'] = (int)($productData['list_price'] * 100);
        }
        

        if (isset($productData['images']) && is_array($productData['images'])) {
            $images = [];
            foreach($productData['images'] as $image){
                if ($image instanceof \Illuminate\Http\UploadedFile) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $image->storeAs('productsImages', $filename, 'public');
                    $images[] = $filename;
                } else {
                    $images[] = $image;
                }
            }
            $productData['images'] = $images;
        }

        return $this->model->create($productData);
    }

    public function updateProduct(array $productData, $storeId, $id)
    {
        if(isset($productData['list_price'])){
            $productData['list_price_cents'] = (int)($productData['list_price'] * 100);
        }
        return $this->model->where('store_id', $storeId)->find($id)->update($productData);
    }

    public function bulkCreateProducts(array $productsData)
    {
        $created = [];
        
        foreach ($productsData as $productData) {
            if (isset($productData['list_price'])) {
                $productData['list_price_cents'] = (int)($productData['list_price'] * 100);
            }
            
            $product = $this->create($productData);
            $created[] = $product;
        }
        
        return $created;
    }

    public function deleteProduct($storeId, $id)
    {
        $product = $this->model->where('store_id', $storeId)->where('id', $id)->first();

        if($product && $product->images && is_array($product->images)){
            foreach($product->images as $image){
                Storage::disk('public')->delete('productsImages/' . $image);
            }
        }
        return $product->delete();
    }

    public function getProductByStore($storeId, $id)
    {
        return $this->model->where('store_id', $storeId)->find($id);
    }


    public function incrementStockQuantity($productId, $quantity)
    {
        return $this->model->whereKey($productId)->increment('stock_quantity', $quantity);
    }

    public function decrementStockQuantity($productId, $quantity)
    {
        return $this->model->whereKey($productId)->decrement('stock_quantity', $quantity);
    }

    public function incrementSoldQuantity($productId, $quantity)
    {
        return $this->model->whereKey($productId)->increment('sold_quantity', $quantity);
    }

    public function decrementSoldQuantity($productId, $quantity)
    {
        return $this->model->whereKey($productId)->decrement('sold_quantity', $quantity);
    }
}