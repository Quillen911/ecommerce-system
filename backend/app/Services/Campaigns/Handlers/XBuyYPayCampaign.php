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

        $x = (int) ($this->campaign->discount_value['x'] ?? 0);
        $y = (int) ($this->campaign->discount_value['y'] ?? 0);

        if ($items->isEmpty() || $x <= 0 || $y <= 0 || $y > $x) {
            return false;
        }

        return $items->sum('quantity') >= $x;
    }

    public function calculateDiscount(array $bagItems): array
    {
        $items = $this->eligibleItems($bagItems);
        if ($items->isEmpty()) {
            return $this->emptyResult();
        }

        $rule = $this->campaign->discount_value;
        $x = (int) ($rule['x'] ?? 0);
        $y = (int) ($rule['y'] ?? 0);

        if ($x <= 0 || $y <= 0 || $y > $x) {
            return $this->emptyResult();
        }

        $totalQty   = $items->sum('quantity');
        $groupCount = intdiv($totalQty, $x);
        $freeCount  = $groupCount * ($x - $y);

        if ($freeCount <= 0) {
            return $this->emptyResult();
        }

        $sorted       = $this->expandItemsByUnitPrice($items);
        $freeLines    = $sorted->slice(0, $freeCount);
        $discountCents = (int) $freeLines->sum('unit_price_cents');

        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => $discountCents,
            'discount'             => $discountCents / 100,
            'eligible_total_cents' => $this->subtotalCents($items),
            'eligible_total'       => $this->subtotalCents($items) / 100,
            'items'                => $this->groupFreeLines($freeLines),
        ];
    }

    protected function eligibleItems(array $bagItems): Collection
    {
        return collect($this->productEligible($bagItems));
    }

    protected function resolveProductId($item): ?int
    {
        return $item->product_id
            ?? optional($item->product)->id
            ?? optional($item->variant)->product_id
            ?? optional($item->variantSize->productVariant)->product_id;
    }

    protected function expandItemsByUnitPrice(Collection $items): Collection
    {
        return $items
            ->flatMap(function ($item) {
                $unit = $this->unitPriceCents($item);

                return array_fill(0, (int) $item->quantity, [
                    'bag_item_id'      => $item->id,
                    'product_id'       => $this->resolveProductId($item),
                    'unit_price_cents' => $unit,
                    'unit_price'       => $unit / 100,
                ]);
            })
            ->sortBy('unit_price_cents')
            ->values();
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
                    'discount'       => $totalDiscountCents / 100,
                ];
            })
            ->values();
    }

    protected function unitPriceCents($item): int
    {
        if (isset($item->unit_price_cents)) {
            return (int) $item->unit_price_cents;
        }

        if (isset($item->unit_price)) {
            return (int) round($item->unit_price * 100);
        }

        return (int) round(optional($item->product)->list_price * 100);
    }

    protected function subtotalCents(Collection $items): int
    {
        return (int) round($items->sum(fn ($item) =>
            $this->unitPriceCents($item) * (int) $item->quantity));
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
