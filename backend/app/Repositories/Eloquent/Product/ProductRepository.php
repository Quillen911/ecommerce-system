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

    /**
     * Kategori ve varyantlarıyla birlikte ürünleri getirir.
     */
    public function getProductsWithCategory($perpage = 100)
    {
        $page = request('page', 1);

        return Cache::remember("products.page.$page", 60, function () use ($perpage) {
            return $this->model
                ->with([
                    'category',
                    'variants.variantAttributes.attribute',
                    'variants.variantAttributes.option',
                ])
                ->orderBy('id')
                ->paginate($perpage);
        });
    }

    /**
     * Tek ürünü kategori ve varyantlarıyla getirir.
     */
    public function getProductWithCategory($id)
    {
        return $this->model
            ->with([
                'category',
                'variants.variantAttributes.attribute',
                'variants.variantAttributes.option',
            ])
            ->find($id);
    }

    /**
     * Mağazaya ait ürünleri kategori ve varyantlarıyla getirir.
     */
    public function getProductsByStore($storeId)
    {
        return $this->model
            ->with([
                'category',
                'variants.variantAttributes.attribute',
                'variants.variantAttributes.option',
            ])
            ->where('store_id', $storeId)
            ->orderBy('id')
            ->get();
    }

    public function getProductBySlugAndStore($storeId, $slug)
    {
        return $this->model->with('store')->where('store_id', $storeId)->where('slug', $slug)->first();
    }

    /**
     * Ürün oluştur.
     */
    public function createProduct(array $productData)
    {
        if (isset($productData['list_price'])) {
            $productData['list_price_cents'] = (int)($productData['list_price'] * 100);
        }

        return $this->create($productData);
    }

    /**
     * Ürün güncelle.
     */
    public function updateProduct(array $productData, $storeId, $id)
    {
        if (isset($productData['list_price'])) {
            $productData['list_price_cents'] = (int)($productData['list_price'] * 100);
        }

        $product = $this->model->where('store_id', $storeId)->where('id', $id)->first();
        
        if (!$product) {
            return false;
        }

        $product->update($productData);

        return $product->fresh(); 
    }


    /**
     * Toplu ürün oluştur.
     */
    public function bulkCreateProducts(array $productsData)
    {
        $created = [];

        foreach ($productsData as $productData) {
            if (isset($productData['list_price'])) {
                $productData['list_price_cents'] = (int)($productData['list_price'] * 100);
            }

            if (isset($productData['images']) && is_array($productData['images'])) {
                $productData['images'] = $this->processImages($productData['images']);
            }

            $product = $this->create($productData);
            $created[] = $product;
        }

        return $created;
    }

    /**
     * Ürünü sil + varyant resimlerini de temizle.
     */
    public function deleteProduct($storeId, $id)
    {
        $product = $this->model->with('variants')->where('store_id', $storeId)->where('id', $id)->first();

        if (!$product) {
            return false;
        }

        // Ana ürün resimlerini sil
        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $image) {
                if (is_array($image)) {
                    foreach ($image as $img) {
                        Storage::disk('public')->delete('productImages/' . $img);
                    }
                } else {
                    Storage::disk('public')->delete('productImages/' . $image);
                }
            }
        }

        // Varyant resimlerini sil
        foreach ($product->variants as $variant) {
            if ($variant->images && is_array($variant->images)) {
                foreach ($variant->images as $image) {
                    if (is_array($image)) {
                        foreach ($image as $img) {
                            Storage::disk('public')->delete('productImages/' . $img);
                        }
                    } else {
                        Storage::disk('public')->delete('productImages/' . $image);
                    }
                }
            }
        }

        return $product->delete();
    }

    /**
     * Mağazaya ait tek ürünü getir.
     */
    public function getProductByStore($storeId, $id)
    {
        return $this->model
            ->with([
                'category',
                'variants.variantAttributes.attribute',
                'variants.variantAttributes.option',
            ])
            ->where('store_id', $storeId)
            ->find($id);
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

    public function getProductBySlug($storeId, $slug)
    {
        return $this->model->where('store_id', $storeId)->where('slug', $slug)->first();
    }
}
