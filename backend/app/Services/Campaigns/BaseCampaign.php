<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;

abstract class BaseCampaign implements CampaignInterface
{
    protected Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    protected function isCampaignActive(): bool
    {
        $now = now();

        if ($this->campaign->starts_at && $this->campaign->starts_at->isFuture()) {
            return false;
        }

        if ($this->campaign->ends_at && $this->campaign->ends_at->isPast()) {
            $this->campaign->forceFill(['is_active' => false])->save();
            return false;
        }

        return (bool) $this->campaign->is_active;
    }

    /**
     * Sepetteki ürünler arasından kampanyanın mağazasına ait olanları döndür.
     */
    protected function productEligible(array $bagItems): array
    {
        $allowedProductIds = $this->campaign->relationLoaded('campaignProducts')
            ? $this->campaign->campaignProducts->pluck('product_id')->filter()->all()
            : [];

        return collect($bagItems)
            ->filter(function ($item) {
                $storeId = $item->store_id
                    ?? $item->product_id
                    ?? optional($item->variant)->store_id
                    ?? optional($item->variantSize->productVariant)->store_id;

                return (int) $storeId === (int) $this->campaign->store_id;
            })
            ->filter(function ($item) use ($allowedProductIds) {
                if (empty($allowedProductIds)) {
                    return true;
                }

                $productId =
                    $item->product_id ??
                    optional($item->product)->id ??
                    optional($item->variant)->product_id ??
                    optional($item->variantSize->productVariant)->product_id;

                return in_array((int) $productId, $allowedProductIds, true);
            })
            ->values()
            ->all();
    }


    abstract public function isApplicable(array $bagItems): bool;

    abstract public function calculateDiscount(array $bagItems): array;

    protected function discountRate(): float
    {
        return max((float) $this->campaign->discount_value, 0) / 100;
    }
}
