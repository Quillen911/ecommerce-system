<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\Refund\OrderRefundCompleteRequest;
use App\Http\Requests\Order\Refund\OrderRefundRejectRequest;
use App\Http\Requests\Order\Refund\OrderRefundReceivedRequest;
use App\Http\Requests\Order\Refund\OrderRefundShippingRequest;
use App\Http\Requests\Order\Refund\OrderRefundStoreRequest;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Services\Order\Services\Refund\OrderRefundService;
use Illuminate\Htt;
use Illuminate\Http\Request;

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

        return response()->json($refund, 201);
    }

}
