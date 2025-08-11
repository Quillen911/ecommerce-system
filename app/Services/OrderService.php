<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Campaign;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ResponseHelper;
use App\Models\CreditCard;
use App\Services\Payments\IyzicoPaymentService;

class OrderService
{
    public function createOrder($user, $products, $campaignManager, $selectedCreditCard)
    {
        $campaigns = Campaign::where('is_active', 1)
                            ->where('starts_at', '<=', now())
                            ->where('ends_at', '>=', now())
                            ->get();

        $bestCampaign = $campaignManager->getBestCampaigns($products->all(), $campaigns);
        $total = $products->sum(function($items) {
            return $items->quantity * $items->product->list_price; 
        });

        $cargo_price = $total >= 50 ? 0 : 10;

        $discount = $bestCampaign['discount'] ?? 0;
        $totally = $total + $cargo_price - $discount;

        $campaign_info = !empty($bestCampaign['description']) ? $bestCampaign['description'] : null;
        $campaign_id = $bestCampaign['campaign_id'] ?? null;

        $credit_card_holder = CreditCard::find($selectedCreditCard)->card_holder_name;
        
        $discountedPriceRate = min(1.0, round($totally / $total, 6));
    
        $productsData = [];
        foreach($products as $product) {
            $productData = Product::find($product->product_id);
            if (!$productData) {
                return new \Exception('Ürün bulunamadı, Sipariş oluşturulamadı!');
            }
            $productsData[] = [
                'product_title' => $product->product->title,
                'product_category_title' => $product->product->category->category_title,
                'product_id' => $product->product_id,
                'quantity' => $product->quantity,
                'list_price' => $product->product->list_price,
                'paid_price' => round(((float)$product->product->list_price * (int)$product->quantity)  * $discountedPriceRate, 2),
            ];
        }
            
        if($campaign_id && $discount > 0){
            $appliedCampaign = Campaign::find($campaign_id);
            if($appliedCampaign){
                $campaignManager->userEligible($appliedCampaign);
                $campaignManager->decreaseUsageLimit($appliedCampaign);
            }
        }
        $exOrder = Order::create([
            'bag_user_id' => $user->id,
            'user_id' => $user->id,
            'credit_card_id' => $selectedCreditCard,
            'card_holder_name' => $credit_card_holder,
            'order_price' => $total,
            'cargo_price' => $cargo_price,
            'discount' => $discount,
            'campaign_id' => $campaign_id,
            'campaign_info' => $campaign_info,
            'campaing_price' => $total + $cargo_price - $discount,
            'paid_price' => 0,
            'currency' => 'TRY',
            'payment_id' => null,
            'conversation_id' => null,
            'payment_status' => 'failed',
            'status' => 'Başarısız Ödeme',
        ]);
        foreach($productsData as $productData) {
            $orderItem = OrderItem::create([
                'order_id' => $exOrder->id,
                'product_id' => $productData['product_id'],
                'product_title' => $productData['product_title'],
                'product_category_title' => $productData['product_category_title'],
                'quantity' => $productData['quantity'],
                'list_price' => $productData['list_price'],
                'paid_price' => $productData['paid_price'],
                'payment_transaction_id' => "",
                'refunded_price' => 0,
                'payment_status' => 'failed',
                'refunded_at' => null,
                'canceled_at' => null,
            ]);
        }
        if(!$exOrder){
            return new \Exception('Sipariş oluşturulamadı!');
        }

        $creditCard = CreditCard::find($selectedCreditCard);
        $iyzicoService = new IyzicoPaymentService();
        $paymentResult = $iyzicoService->processPayment($exOrder, $creditCard, $totally);

        if ($paymentResult['success']) {
            $exOrder->update([
                'paid_price'      => $paymentResult['paid_price'] ?? 0,
                'currency'        => $paymentResult['currency'] ?? 'TRY',
                'payment_id'      => $paymentResult['payment_id'] ?? null,
                'conversation_id' => $paymentResult['conversation_id'] ?? null,
                'payment_status'  => $paymentResult['payment_status'],
                'status'          => 'pending',
            ]);

            foreach ($paymentResult['payment_transaction_id'] as $itemId => $txId) {
                $orderItem = OrderItem::where('order_id', $exOrder->id)
                    ->where('product_id', $itemId)
                    ->first();
                if($orderItem){
                $orderItem->update([
                        'paid_price' => $orderItem->paid_price,
                        'payment_transaction_id' => $txId,
                        'payment_status' => $paymentResult['payment_status'],
                    ]);
                }
            }

            
            foreach ($productsData as $p) {
                Product::whereKey($p['product_id'])->decrement('stock_quantity', (int) $p['quantity']);
            }

            return ['success' => true, 'order_id' => $exOrder->id];
        } else {
            $exOrder->update([
                'payment_status' => 'failed',
                'status' => 'Başarısız Ödeme',
            ]);
            $exOrder->delete();
            foreach($exOrder->orderItems as $item){
                $item->forceDelete();
            }
            $errorMessage = $paymentResult['error'];
            if (isset($paymentResult['error_code'])) {
                $errorMessage .= ' (Hata Kodu: ' . $paymentResult['error_code'] . ')';
            }
            
            return [
                'success' => false,
                'error' => $errorMessage
            ];
        }

    }
    public function showOrder($user, $id)
    {
        return Order::where('bag_user_id', $user)
                    ->where('id',$id)
                    ->first();
    }

}