<?php

namespace App\Observers;

use App\Models\ProductVariantImage;
use App\Jobs\IndexProductToElasticsearch;
use App\Jobs\DeleteProductToElasticsearch;

class ProductVariantImageObserver
{
    public function saved(ProductVariantImage $productVariantImage): void
    {
        // ✅ GÜVENLİ İLİŞKİ YÜKLEME
        try {
            // İlişkiyi yükle
            $productVariantImage->load('productVariant.product');
            
            if ($productVariantImage->productVariant && $productVariantImage->productVariant->product) {
                dispatch(new IndexProductToElasticsearch($productVariantImage->productVariant->product->id));
            }
        } catch (\Exception $e) {
            // Hata durumunda logla ama işlemi durdurma
            \Log::error('ProductVariantImageObserver error: ' . $e->getMessage());
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
        dispatch(new DeleteProductToElasticsearch($productVariantImage->variant->product_id));
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
