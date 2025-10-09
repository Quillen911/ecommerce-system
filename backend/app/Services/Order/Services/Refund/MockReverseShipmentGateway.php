<?php

namespace App\Services\Order\Services\Refund;

use App\Models\OrderRefund;
use Illuminate\Support\Str;
use App\Services\Order\Contracts\Refund\ReverseShipmentGatewayInterface;

class MockReverseShipmentGateway implements ReverseShipmentGatewayInterface
{
    public function createReverseShipment(array $payload): array
    {
        return [
            'provider'        => 'mock-courier',
            'tracking_number' => 'MR-' . Str::random(10),
        ];
    }

    public function cancelReverseShipment(OrderRefund $refund): void
    {
        //mock
    }
}