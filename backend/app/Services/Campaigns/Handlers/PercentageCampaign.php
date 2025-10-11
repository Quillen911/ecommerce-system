<?php

namespace App\Services\Campaigns\Handlers;

use App\Services\Campaigns\BaseCampaign;
use Illuminate\Support\Collection;

class PercentageCampaign extends BaseCampaign
{
    public function isApplicable(array $bagItems): bool
    {
        if (! $this->isCampaignActive() || ! $this->eligibileMinBag($bagItems) ) {
            return false;
        }
        
        return ! $this->eligibleItems($bagItems)->isEmpty();
    }

    public function calculateDiscount(array $bagItems): array
    {
        $rate = $this->discountRate();

        if ($rate <= 0) {
            return $this->emptyResult();
        }

        $items = $this->eligibleItems($bagItems);

        if ($items->isEmpty()) {
            return $this->emptyResult();
        }

        $subtotalCents = $this->subtotalCents($items);
        $discountCents = round($subtotalCents * $rate);
        $perProductDiscount = $this->perItemDiscount($items, $rate);

        return [
            'discount_cents'       => max($discountCents, 0),
            'eligible_total_cents' => $subtotalCents,
            'items'                => $perProductDiscount,
        ];
    }

    protected function eligibleItems(array $bagItems): Collection
    {
        return collect($this->productEligible($bagItems));
    }

    protected function subtotalCents(Collection $items): int
    {
        return round($items->sum(fn ($item) =>
            $item->unit_price_cents * $item->quantity));
    }

    protected function perItemDiscount(Collection $items, float $rate): Collection
    {
        return $items->map(function($item) use ($rate) {
            return [
                'discount_item_id' => $item->id,
                'discount_item_product_id' => $item->variant->product_id,
                'quantity' => $item->quantity,
                'discount' => ($item->unit_price_cents * $item->quantity * $rate) 
            ];
        });
    }
    
    protected function emptyResult(): array
    {
        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => 0,
            'discount'             => 0.0,
            'eligible_total_cents' => 0,
            'eligible_total'       => 0.0,
            'items'                => collect(),
        ];
    }
}
