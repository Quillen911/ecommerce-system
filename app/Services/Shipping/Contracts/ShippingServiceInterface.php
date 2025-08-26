<?php

namespace App\Services\Shipping\Contracts;

interface ShippingServiceInterface
{
    public function createShipment(array $data): array;
    //public function getTrackingInfo(string $trackingNumber): array;
    //public function cancelShipment(string $trackingNumber): array;
    //public function getShippingCompanies(): array;
}