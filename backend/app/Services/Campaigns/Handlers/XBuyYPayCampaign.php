<?php

namespace App\Services\Campaigns\Handlers;

use App\Services\Campaigns\BaseCampaign;
use Illuminate\Support\Collection;

class XBuyYPayCampaign extends BaseCampaign
{
    public function isApplicable(array $bagItems): bool
    {
        if (! $this->isCampaignActive()) {
            return false;
        }
    
        $items = $this->eligibleItems($bagItems);
    
        if ($items->isEmpty()) {
            return false;
        }
    
        if (! $this->eligibleMinBag($items->all())) {
            return false;
        }
    
        return true;
    }

    public function calculateDiscount(array $bagItems): array
    {
        $items = $this->eligibleItems($bagItems);
        if ($items->isEmpty()) {
            return $this->emptyResult();
        }

        $x = ($this->campaign->buy_quantity ?? 0);
        $y = ($this->campaign->pay_quantity ?? 0);

        if ($x <= 0 || $y <= 0 || $y > $x) {
            return $this->emptyResult();
        }

        $totalQty   = $items->sum('quantity');
        $groupCount = intdiv($totalQty, $x);
        $freeCount  = $groupCount * ($x - $y);
        
        if ($freeCount <= 0) {
            return $this->emptyResult();
        }
        $freeLines    = $items->slice(0, $freeCount);
        $discountCents =  $freeLines->sum('unit_price_cents') * $freeCount;
        
        //dd($freeLines,$totalQty, $groupCount, $freeCount, $discountCents);
        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => $discountCents,
            'eligible_total_cents' => $this->subtotalCents($items),
            'items'                => $this->groupFreeLines($freeLines),
        ];
    }

    protected function eligibleItems(array $bagItems): Collection
    {
        return collect($this->productEligible($bagItems));
    }

    protected function groupFreeLines(Collection $lines): Collection
    {
        return $lines
            ->groupBy(fn ($line) => $line['bag_item_id'])
            ->map(function ($group) {
                $totalDiscountCents = $group->sum('unit_price_cents');

                return [
                    'bag_item_id'    => $group->first()['bag_item_id'],
                    'product_id'     => $group->first()['product_id'],
                    'quantity'       => $group->count(),
                    'discount_cents' => $totalDiscountCents,
                ];
            })
            ->values();
    }

    protected function subtotalCents(Collection $items): int
    {
        return $items->sum(fn ($item) =>
            $item->unit_price_cents * $item->quantity);
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
