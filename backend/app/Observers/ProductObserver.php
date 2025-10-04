<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Category;
use App\Jobs\IndexProductToElasticsearch;
use App\Jobs\DeleteProductToElasticsearch;

class ProductObserver
{

    public function saved(Product $product): void
    {
        dispatch(new IndexProductToElasticsearch($product->id));
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        if ($product->category_id) {
            ProductCategory::create([
                'product_id' => $product->id,
                'category_id' => $product->category_id,
                'is_primary' => true,
            ]);
            
            $category = Category::find($product->category_id);
            if ($category && $category->parent_id) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $category->parent_id,
                    'is_primary' => false,
                ]);
            }
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->isDirty('category_id')) {
            ProductCategory::where('product_id', $product->id)->delete();
            
            if ($product->category_id) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'is_primary' => true,
                ]);
                
                $category = Category::find($product->category_id);
                if ($category && $category->parent_id) {
                    ProductCategory::create([
                        'product_id' => $product->id,
                        'category_id' => $category->parent_id,
                        'is_primary' => false,
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        ProductCategory::where('product_id', $product->id)->delete();
        
        dispatch(new DeleteProductToElasticsearch($product->id));
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
