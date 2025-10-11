<?php

namespace App\Services\Campaigns\Handlers;

use App\Services\Campaigns\BaseCampaign;
use Illuminate\Support\Collection;

class PercentageCampaign extends BaseCampaign
{
    public function isApplicable(array $bagItems): bool
    {
        if (! $this->isCampaignActive()) {
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
        $discountCents = (int) round($subtotalCents * $rate);

        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => max($discountCents, 0),
            'discount'             => max($discountCents, 0) / 100,
            'eligible_total_cents' => $subtotalCents,
            'eligible_total'       => $subtotalCents / 100,
            'items'                => $this->perItemDiscount($items, $rate),
        ];
    }

    protected function eligibleItems(array $bagItems): Collection
    {
        return collect($this->productEligible($bagItems));
    }

    protected function subtotalCents(Collection $items): int
    {
        return (int) round($items->sum(fn ($item) =>
            $this->unitPriceCents($item) * (int) $item->quantity));
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

    protected function perItemDiscount(Collection $items, float $rate): Collection
    {
        return $items->map(function ($item) use ($rate) {
            $unitCents   = $this->unitPriceCents($item);
            $quantity    = (int) $item->quantity;
            $lineCents   = $unitCents * $quantity;
            $discountCents = min((int) round($lineCents * $rate), $lineCents);

            return [
                'bag_item_id'             => $item->id,
                'product_id'              => $item->product_id
                    ?? optional($item->product)->id
                    ?? optional($item->variant)->product_id
                    ?? optional($item->variantSize->productVariant)->product_id,
                'quantity'                => $quantity,
                'unit_price_cents'        => $unitCents,
                'line_total_cents'        => $lineCents,
                'discount_cents'          => $discountCents,
                'discount'                => $discountCents / 100,
                'discounted_total_cents'  => $lineCents - $discountCents,
                'discounted_total'        => ($lineCents - $discountCents) / 100,
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
