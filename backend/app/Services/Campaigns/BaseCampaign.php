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
            $this->campaign->is_active = false;
            $this->campaign->save();
            return false;
        }
        if($this->campaign->usage_limit <= $this->campaign->usage_count){
            $this->campaign->is_active = false;
            $this->campaign->save();
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
        $productIds   = null;
        $categoryIds  = null;

        if ($this->campaign->relationLoaded('campaignProducts') && $this->campaign->campaignProducts->isNotEmpty()) {
            $productIds = $this->campaign->campaignProducts
                ->pluck('product_id')
                ->filter()
                ->values()
                ->all();
        } elseif ($this->campaign->relationLoaded('campaignCategories') && $this->campaign->campaignCategories->isNotEmpty()) {
            $categoryIds = $this->campaign->campaignCategories
                ->pluck('category_id')
                ->filter()
                ->values()
                ->all();
        }

        return collect($bagItems)
            ->filter(function ($item) {
                $campaignStore = $this->campaign->store_id;

                if (is_null($campaignStore)) {
                    return true;
                }

                $itemStore = $item->store_id
                    ?? optional($item->product)->store_id
                    ?? optional($item->variant)->store_id
                    ?? optional(optional($item->variantSize)->productVariant)->product->store_id;

                return $itemStore === $campaignStore;
            })
            ->filter(function ($item) use ($productIds, $categoryIds) {
                $productId = $item->product_id
                    ?? optional($item->product)->id
                    ?? optional($item->variant)->product_id
                    ?? optional(optional($item->variantSize)->productVariant)->product_id;

                if ($productIds !== null) {
                    return $productId && in_array($productId, $productIds, true);
                }

                if ($categoryIds !== null) {
                    $categoryId = optional($item->product)->category_id
                        ?? optional(optional($item->variant)->product)->category_id
                        ?? optional(optional(optional($item->variantSize)->productVariant)->product)->category_id;

                    return $categoryId && in_array($categoryId, $categoryIds, true);
                }

                return true;
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
    
        $result = ($totalCents / 100) >= (float) $this->campaign->min_subtotal;
        if(!$result){
            throw new \Exception('Kampanyanın uygulanabileceği minimum sepet tutarına ' . (round($this->campaign->min_subtotal-$totalCents / 100, 2)) . ' kaldı');
        }
        return $result;
    }

    abstract public function isApplicable(array $bagItems): bool;

    abstract public function calculateDiscount(array $bagItems): array;
}
