<?php

namespace App\Observers;

use App\Models\VariantAttribute;
use App\Jobs\IndexProductToElasticsearch;
use App\Jobs\DeleteProductToElasticsearch;

class VariantAttributeObserver
{
    public function saved(VariantAttribute $variantAttribute): void
    {
        dispatch(new IndexProductToElasticsearch($variantAttribute->variant->product_id));
    }
    /**
     * Handle the VariantAttribute "created" event.
     */
    public function created(VariantAttribute $variantAttribute): void
    {
        //
    }

    /**
     * Handle the VariantAttribute "updated" event.
     */
    public function updated(VariantAttribute $variantAttribute): void
    {
        //
    }

    /**
     * Handle the VariantAttribute "deleted" event.
     */
    public function deleted(VariantAttribute $variantAttribute): void
    {
        dispatch(new DeleteProductToElasticsearch($variantAttribute->variant->product_id));
    }

    /**
     * Handle the VariantAttribute "restored" event.
     */
    public function restored(VariantAttribute $variantAttribute): void
    {
        //
    }

    /**
     * Handle the VariantAttribute "force deleted" event.
     */
    public function forceDeleted(VariantAttribute $variantAttribute): void
    {
        //
    }
}
