<?php

namespace App\Services\Campaigns\Handlers;

use App\Services\Campaigns\BaseCampaign;
use Illuminate\Support\Collection;

class FixedCampaign extends BaseCampaign
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

        $minSubtotal = $this->campaign->min_quantity;
        if ($minSubtotal && $this->subtotal($items) < (float) $minSubtotal) {
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

        $discount = max((float) $this->campaign->discount_value, 0);
        $subtotal = $this->subtotal($items);

        if ($discount <= 0 || $subtotal < $discount) {
            return $this->emptyResult($subtotal);
        }

        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => (int) round($discount * 100),
            'discount'             => $discount,
            'eligible_total_cents' => (int) round($subtotal * 100),
            'eligible_total'       => $subtotal,
            'items'                => $this->splitDiscount($items, $discount),
        ];
    }

    protected function eligibleItems(array $bagItems): Collection
    {
        return collect($this->productEligible($bagItems));
    }

    protected function subtotal(Collection $items): float
    {
        return $items->sum(fn ($item) => $this->unitPrice($item) * (int) $item->quantity);
    }

    protected function unitPrice($item): float
    {
        return $item->unit_price
            ?? ($item->unit_price_cents / 100 ?? optional($item->product)->list_price ?? 0.0);
    }

    protected function resolveProductId($item): ?int
    {
        return $item->product_id
            ?? optional($item->product)->id
            ?? optional($item->variant)->product_id
            ?? optional($item->variantSize->productVariant)->product_id;
    }

    protected function splitDiscount(Collection $items, float $discount): Collection
    {
        $total = $this->subtotal($items);

        return $items->map(function ($item) use ($discount, $total) {
            $unitCents  = (int) round($this->unitPrice($item) * 100);
            $quantity   = (int) $item->quantity;
            $lineCents  = $unitCents * $quantity;
            $share      = $total > 0 ? ($lineCents / ($total * 100)) : 0;
            $lineDiscountCents = (int) round($discount * 100 * $share);

            return [
                'bag_item_id'            => $item->id,
                'product_id'             => $this->resolveProductId($item),
                'quantity'               => $quantity,
                'unit_price_cents'       => $unitCents,
                'line_total_cents'       => $lineCents,
                'discount_cents'         => $lineDiscountCents,
                'discount'               => $lineDiscountCents / 100,
                'discounted_total_cents' => max($lineCents - $lineDiscountCents, 0),
                'discounted_total'       => max($lineCents - $lineDiscountCents, 0) / 100,
            ];
        });
    }


    protected function emptyResult(float $subtotal = 0): array
    {
        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => 0,
            'discount'             => 0.0,
            'eligible_total_cents' => (int) round($subtotal * 100),
            'eligible_total'       => $subtotal,
            'items'                => collect(),
        ];
    }
}
