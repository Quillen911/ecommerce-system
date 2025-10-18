<?php

namespace App\Services\Campaigns\Handlers;

use App\Services\Campaigns\BaseCampaign;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class FixedCampaign extends BaseCampaign
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
            dd($this->campaign->campaign_usages()->where('user_id', $this->user->id)->count());
            throw ValidationException::withMessages([
                'campaign' => [
                    'Bu kampanya kullanım limitine ulaştı.',
                ],
            ]);
        }
    }
    public function calculateDiscount(array $bagItems): array
    {
        $items = $this->eligibleItems($bagItems);
        if ($items->isEmpty()) {
            return $this->emptyResult();
        }

        $discountCents = (int) round(max($this->campaign->discount_value, 0) * 100);
        $subtotalCents = $this->subtotalCents($items);

        if ($discountCents <= 0 || $subtotalCents < $discountCents) {
            return $this->emptyResult($subtotalCents);
        }

        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'code'                  => $this->campaign->code,
            'description'          => $this->campaign->description,
            'discount_cents'       => $discountCents,
            'eligible_total_cents' => $subtotalCents,
            'items'                => $this->splitDiscount($items, $discountCents),
        ];
    }

    protected function eligibleItems(array $bagItems): Collection
    {
        return collect($this->productEligible($bagItems));
    }

    protected function subtotalCents(Collection $items): int
    {
        return (int) $items->sum(fn ($item) => $item->unit_price_cents * $item->quantity);
    }

    protected function splitDiscount(Collection $items, int $discountCents): Collection
    {
        $totalCents = $this->subtotalCents($items);

        return $items->map(function ($item) use ($discountCents, $totalCents) {
            $quantity   = $item->quantity;
            $lineCents  = $item->unit_price_cents * $quantity;
            $share      = $totalCents > 0 ? ($lineCents / $totalCents) : 0;
            $lineDiscountCents = (int) round($discountCents * $share);
            
            return [
                'bag_item_id'            => $item->id,
                'product_id'             => $item->variant->product_id,
                'quantity'               => $quantity,
                'unit_price_cents'       => $item->unit_price_cents,
                'line_total_cents'       => $lineCents,
                'discount_cents'         => $lineDiscountCents,
                'discounted_total_cents' => max($lineCents - $lineDiscountCents, 0),
            ];
        });
    }

    protected function emptyResult(int $subtotalCents = 0): array
    {
        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => 0,
            'discount'             => 0,
            'eligible_total_cents' => $subtotalCents,
            'eligible_total'       => $subtotalCents / 100,
            'items'                => collect(),
        ];
    }
}
