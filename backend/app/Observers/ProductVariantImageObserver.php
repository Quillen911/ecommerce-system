<?php

namespace App\Observers;

use App\Models\ProductVariantImage;
use App\Models\ProductVariant;
use App\Jobs\IndexProductToElasticsearch;
use App\Jobs\DeleteProductToElasticsearch;
use Illuminate\Support\Facades\Log;

class ProductVariantImageObserver
{
    public function saved(ProductVariantImage $productVariantImage): void
    {
        try {
            $productVariantImage->load('productVariant.product');
            
            if ($productVariantImage->productVariant && $productVariantImage->productVariant->product) {
                dispatch(new IndexProductToElasticsearch($productVariantImage->productVariant->product->id));
            }
        } catch (\Exception $e) {
            Log::error('ProductVariantImageObserver error: ' . $e->getMessage());
        }
    }
    /**
     * Handle the ProductVariantImage "created" event.
     */
    public function created(ProductVariantImage $productVariantImage): void
    {
        //
    }

    /**
     * Handle the ProductVariantImage "updated" event.
     */
    public function updated(ProductVariantImage $productVariantImage): void
    {
        //
    }

    /**
     * Handle the ProductVariantImage "deleted" event.
     */
    public function deleted(ProductVariantImage $productVariantImage): void
    {
        $productId = optional($productVariantImage->variant)->product_id
            ?? ProductVariant::whereKey($productVariantImage->product_variant_id)->value('product_id');

        if ($productId) {
            dispatch(new DeleteProductToElasticsearch($productId));
        }
    }
    /**
     * Handle the ProductVariantImage "restored" event.
     */
    public function restored(ProductVariantImage $productVariantImage): void
    {
        //
    }

    /**
     * Handle the ProductVariantImage "force deleted" event.
     */
    public function forceDeleted(ProductVariantImage $productVariantImage): void
    {
        //
    }
}
