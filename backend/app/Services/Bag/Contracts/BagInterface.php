<?php

namespace App\Services\Bag\Contracts;

interface BagInterface
{
    public function getBag();
    public function addToBag($variantSizeId, $quantity = 1);
    public function selectCampaign(int $campaignId): array;
    public function unselectCampaign(): array;
    public function showBagItem($bagItemId);
    public function updateBagItem($bagItemId, $quantity);
    public function destroyBagItem($bagItemId);
}