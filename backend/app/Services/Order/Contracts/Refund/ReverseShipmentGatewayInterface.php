<?php

namespace App\Services\Order\Contracts\Refund;

use App\Models\OrderRefund;

interface ReverseShipmentGatewayInterface
{
    public function createReverseShipment(array $payload): array;

    public function cancelReverseShipment(OrderRefund $refund): void;
}
