<?php

namespace App\Services\Checkout;

use App\Models\CheckoutSession;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Services\Checkout\CheckoutSessionService;
use App\Services\Payments\Contracts\PaymentGatewayInterface;

class CheckoutOrderService
{
    
    public function __construct(
        private readonly CheckoutSessionService $checkoutSessionService,
    ) {
    }

    public function createOrderFromSession(User $user, CheckoutSession $session): Order
    {
        return DB::transaction(function () use ($user, $session) {
            $bag   = $session->bag_snapshot;
            $ship  = $session->shipping_data;
            $bill  = $session->billing_data ?? $ship;
            $pay   = $session->payment_data;

            $order = Order::create([
                'user_id'                 => $user->id,
                'bag_id'                  => $session->bag_id,
                'user_shipping_address_id'=> $ship['shipping_address_id'],
                'user_billing_address_id' => $bill['billing_address_id'] ?? $ship['shipping_address_id'],
                'campaign_id'             => null,
                'campaign_info'           => null,
                'order_number'            => Str::upper(Str::random(10)),
                'subtotal_cents'          => $bag['totals']['total_cents'],
                'discount_cents'          => $bag['totals']['discount_cents'] ?? 0,
                'tax_total_cents'         => $bag['totals']['tax_total_cents'] ?? 0,
                'cargo_price_cents'       => $bag['totals']['cargo_cents'],
                'campaign_price_cents'    => $bag['totals']['campaign_price_cents'] ?? 0,
                'grand_total_cents'       => $bag['totals']['final_cents'],
                'currency'                => 'TRY',
                'status'                  => 'pending',
            ]);

            foreach ($bag['items'] as $item) {
                OrderItem::create([
                    'order_id'               => $order->id,
                    'product_id'             => $item['product_id'],
                    'variant_size_id'        => $item['variant_size_id'],
                    'store_id'               => $item['store_id'],
                    'product_title'          => $item['product_title'],
                    'product_category_title' => $item['category_title'] ?? null,
                    'quantity'               => $item['quantity'],
                    'price_cents'            => $item['unit_price_cents'],
                    'discount_price_cents'   => $item['discount_price_cents'] ?? 0,
                    'paid_price_cents'       => $item['total_price_cents'],
                    'tax_rate'               => $item['tax_rate'] ?? 0,
                    'tax_amount_cents'       => $item['tax_amount_cents'] ?? 0,
                    'payment_transaction_id' => $item['payment_data']['intent']['payment_transaction_id'],
                    'status'                 => 'pending',
                    'payment_status'         => 'paid',
                ]);
            }

            if (! empty($pay['intent'])) {
                $result = $pay['intent'];

                $payment = Payment::create([
                    'order_id'                => $order->id,
                    'payment_method_id'       => $pay['payment_method_id'],
                    'provider'                => $pay['provider'],
                    'provider_payment_id'     => $result['payment_id'],
                    'conversation_id'         => $result['conversation_id'] ?? null,
                    'amount_cents'            => $result['authorized_amount_cents'],
                    'authorized_amount_cents' => $result['authorized_amount_cents'],
                    'currency'                => $result['currency'],
                    'status'                  => $result['status'],
                    'payload'                 => $result['raw'] ?? null,
                ]);

                PaymentEvent::create([
                    'payment_id' => $payment->id,
                    'provider'   => $payment->provider,
                    'event_type' => 'payment_authorized',
                    'payload'    => $result['raw'] ?? null,
                ]);

                if (($pay['save_card'] ?? false) && ($pay['new_card_payload'] ?? null)) {

                    $storedMethod = app(PaymentGatewayInterface::class, ['provider' => $provider])->storePaymentMethod(
                        
                        $user,
                        new PaymentMethod([
                            'provider' => $pay['provider'],
                            'type'     => 'card',
                            'is_active'=> true,
                        ]),
                        $pay['new_card_payload']
                    );

                    $pay['payment_method_id'] = $storedMethod->id;
                    $payment->payment_method_id = $storedMethod->id;
                    $payment->save();
                    $session->payment_data = $pay;
                    $session->save();
                }
            }

            return $order;
        });
    }
}
