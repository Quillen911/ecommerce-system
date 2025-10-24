<?php

namespace App\Services\Campaigns\Handlers;

use App\Services\Campaigns\BaseCampaign;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class XBuyYPayCampaign extends BaseCampaign
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

        if (! $this->eligibleXbuyYpay($items->all())) {
            return false;
        }

        return true;
    }

    protected function checkPerUserLimit(): void
    {
        if ($this->campaign->per_user_limit &&
            $this->campaign->campaign_usages()->where('user_id', $this->user->id)->count() >= $this->campaign->per_user_limit) {
            throw ValidationException::withMessages([
                'campaign' => ['Bu kampanya kullanım limitine ulaştı.'],
            ]);
        }
    }

    public function calculateDiscount(array $bagItems): array
    {
        $items = $this->eligibleItems($bagItems);
        if ($items->isEmpty()) {
            return $this->emptyResult();
        }

        $x = (int) ($this->campaign->buy_quantity ?? 0);
        $y = (int) ($this->campaign->pay_quantity ?? 0);

        if ($x <= 0 || $y <= 0 || $y > $x) {
            return $this->emptyResult();
        }

        $totalQty   = $items->sum('quantity');
        $groupCount = intdiv($totalQty, $x);
        $freeCount  = $groupCount * ($x - $y);

        if ($freeCount <= 0) {
            return $this->emptyResult();
        }

        $freeUnits = $this->buildFreeUnits($items, $freeCount);

        if ($freeUnits->isEmpty()) {
            return $this->emptyResult();
        }

        $discountCents = (int) $freeUnits->sum('unit_price_cents');

        return [
            'campaign_id'          => $this->campaign->id,
            'store_id'             => $this->campaign->store_id,
            'description'          => $this->campaign->description,
            'discount_cents'       => $discountCents,
            'eligible_total_cents' => $this->subtotalCents($items),
            'items'                => $this->groupFreeUnits($freeUnits),
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

    /**
     * Her ürünü adet adet düz bir listeye açar, artan fiyata göre sıralar ve
     * kampanya kapsamında ücretsiz olacak ürünleri döndürür.
     */
    protected function buildFreeUnits(Collection $items, int $freeCount): Collection
    {
        $unitLines = collect();

        foreach ($items as $item) {
            $productId = $item->variant->product_id ?? $item->product_id ?? null;

            for ($i = 0; $i < $item->quantity; $i++) {
                $unitLines->push([
                    'bag_item_id'       => $item->id,
                    'product_id'        => $productId,
                    'unit_price_cents'  => (int) $item->unit_price_cents,
                ]);
            }
        }

        return $unitLines
            ->sortBy('unit_price_cents')
            ->take($freeCount)
            ->values();
    }

    /**
     * Seçilen ücretsiz birimleri bag_item_id bazında toplayıp kampanya sonucuna hazırlar.
     */
    protected function groupFreeUnits(Collection $freeUnits): Collection
    {
        return $freeUnits
            ->groupBy('bag_item_id')
            ->mapWithKeys(function ($group, $bagItemId) {
                $quantity   = $group->count();
                $discount   = (int) $group->sum('unit_price_cents');

                return [
                    (int) $bagItemId => [
                        'bag_item_id'                     => (int) $bagItemId,
                        'product_id'                      => $group->first()['product_id'],
                        'quantity'                        => $quantity,
                        'discount_cents'                  => $discount,
                        'discounted_total_cents'          => 0, // bu kampanyada ücretsiz ürünler 0’a düşer
                        'per_item_discounted_price_cents' => 0,
                    ],
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
