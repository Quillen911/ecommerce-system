<?php

namespace App\Services\Campaigns\Handlers;

use App\Services\Campaigns\BaseCampaign;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PercentageCampaign extends BaseCampaign
{
    public function isApplicable(array $bagItems): bool
    {
        if (! $this->isCampaignActive()) {
            return false;
        }
        $this->checkPerUserLimit();
        $items = $this->eligibleItems($bagItems);
        
        if ($items->isEmpty()) {
            return false;
        }
    
        if (! $this->eligibleMinBag($items->all())) {
            return false;
        }
    
        return true;
    }
    protected function checkPerUserLimit(): void
    {
        if ($this->campaign->per_user_limit && $this->campaign->campaign_usages()->where('user_id', $this->user->id)->count() >= $this->campaign->per_user_limit) {
            throw ValidationException::withMessages([
                'campaign' => [
                    'Bu kampanyanın kullanım limitine ulaştınız.',
                ],
            ]);
        }
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
            $discountedPrice = $item->unit_price_cents * $item->quantity * $rate;
            return [
                'bag_item_id' => $item->id,
                'product_id' => $item->variant->product_id,
                'quantity' => $item->quantity,
                'discount_cents' => round($discountedPrice) ,
                'discounted_total_cents' => ($item->unit_price_cents * $item->quantity - $discountedPrice) ,
                'per_item_discounted_price_cents' => (int) round(($item->unit_price_cents * $item->quantity - $discountedPrice) / ($item->quantity)) 
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
