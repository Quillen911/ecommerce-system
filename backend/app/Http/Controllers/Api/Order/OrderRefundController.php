<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\Refund\OrderRefundStoreRequest;
use App\Models\Order;
use App\Services\Order\Services\Refund\OrderRefundService;
use App\Http\Resources\Order\Refund\OrderRefundResource;

class OrderRefundController extends Controller
{
    public function __construct(
        private readonly OrderRefundService $refundService
    ) {
    }

    public function store(OrderRefundStoreRequest $request, Order $order)
    {
        $payload = $request->validated();

        $refund = $this->refundService->createRefund($order, $payload);

        return new OrderRefundResource($refund);
    }

}
