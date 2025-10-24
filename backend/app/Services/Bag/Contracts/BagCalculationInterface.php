<?php

namespace App\Services\Bag\Contracts;

interface BagCalculationInterface
{
    public function getBestCampaign($bagItems, $user);
    public function calculateTotal($bagItems);
    public function calculateCargoPrice($total);
    public function calculateItemCargoPrice($bagItems);
    public function itemFinalPrice(array $perItemCargoPrice, array $perItemPrice, array $discountItems);
}