<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\Refund\PaymentWebhookRequest;
use App\Http\Requests\Order\Refund\ShipmentWebhookRequest;
use App\Jobs\Refund\HandlePaymentWebhookJob;
use App\Jobs\Refund\HandleShipmentWebhookJob;
use Illuminate\Http\JsonResponse;

class OrderRefundWebhookController extends Controller
{
    public function handleShipmentStatus(ShipmentWebhookRequest $request): JsonResponse
    {
        HandleShipmentWebhookJob::dispatch($request->validated());

        return response()->json(['status' => 'accepted'], 202);
    }

    public function handlePaymentStatus(PaymentWebhookRequest $request): JsonResponse
    {
        HandlePaymentWebhookJob::dispatch($request->validated());

        return response()->json(['status' => 'accepted'], 202);
    }
}
