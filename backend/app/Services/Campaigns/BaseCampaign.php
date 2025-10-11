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

        if ($this->campaign->starts_at && $this->campaign->starts_at->isFuture()) {
            return false;
        }

        if ($this->campaign->ends_at && $this->campaign->ends_at->isPast()) {
            $this->campaign->forceFill(['is_active' => false])->save();
            return false;
        }

        return $this->campaign->is_active;
    }

    protected function discountRate(): float
    {
        return max($this->campaign->discount_value, 0) / 100;
    }

    protected function productEligible(array $bagItems): array
    {
        $allowedProductIds = null;
    
        if ($this->campaign->relationLoaded('campaignProducts')) {
            $allowedProductIds = $this->campaign->campaignProducts
                ->pluck('product_id')
                ->filter()
                ->all();
        }
    
        return collect($bagItems)
            ->filter(fn ($item) =>
                ($item->store_id ?? optional($item->product)->store_id) === $this->campaign->store_id
            )
            ->filter(function ($item) use ($allowedProductIds) {
                if (empty($allowedProductIds)) {
                    return true;
                }
    
                $productId = $item->product_id
                    ?? optional($item->product)->id
                    ?? optional($item->variant)->product_id
                    ?? optional(optional($item->variantSize)->productVariant)->product_id;
    
                return $productId && in_array($productId, $allowedProductIds, true);
            })
            ->values()
            ->all();
    }
    
    protected function eligibleMinBag(array $items): bool
    {
        if (! $this->campaign->min_subtotal) {
            return true;
        }
    
        $totalCents = collect($items)->sum(function ($item) {
            $unitCents = $item->unit_price_cents
                ?? ($item->unit_price ? $item->unit_price * 100 : null)
                ?? optional($item->product)->list_price * 100;
    
            return ($unitCents ?? 0) * (int) $item->quantity;
        });
    
        return ($totalCents / 100) >= (float) $this->campaign->min_subtotal;
    }

    abstract public function isApplicable(array $bagItems): bool;

    abstract public function calculateDiscount(array $bagItems): array;
}
