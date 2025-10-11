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

        return (bool) $this->campaign->is_active;
    }

    protected function productEligible(array $bagItems): array
    {
        if($this->campaign->relationLoaded('campaignProducts')){
            $allowedProductIds = $this->campaign->campaignProducts->pluck('product_id')->filter()->all();
        }
        if(!$allowedProductIds){
            return [];
        }

        return collect($bagItems)->filter(function ($item) {
                $storeId = $item->store_id;
                return $storeId === $this->campaign->store_id;
            })
            ->filter(function ($item) use ($allowedProductIds) {
                if (empty($allowedProductIds)) {
                    return true;
                }

                $productId =$item->variant->product_id ;

                return in_array($productId, $allowedProductIds, true);
            })
            ->values()
            ->all();
    }

    protected function discountRate(): float
    {
        return max($this->campaign->discount_value, 0) / 100;
    }

    protected function eligibileMinBag($bagItems)
    {
        $minSubTotal = $this->campaign->min_subtotal;
        if($minSubTotal){
            $total = collect($bagItems)->sum(function($item) {
                return ($item->unit_price_cents * $item->quantity)/100;
            });
           
            if($total < $minSubTotal){
                $remaining = $minSubTotal - $total;
                throw new \Exception('Sepet Tutarı Yetersiz. Kalan: ' . $remaining . 'TL');
            }
        }

        return true;
    }

    abstract public function isApplicable(array $bagItems): bool;

    abstract public function calculateDiscount(array $bagItems): array;
}
