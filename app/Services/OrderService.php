<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Campaign;
use App\Jobs\CreateOrderJob;
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

        $orderData = [
            'user_id' => $user->id,
            'products' => $products->map(function($item){
                return [
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->list_price,
                ];
               
            })->toArray(),
            'campaign_id' => $campaign_id,
            'campaign_info' => $campaign_info,
            'total' => $total,
            'cargo_price' => $cargo_price,
            'discount' => $discount,
            'status' => 'bekliyor',
            'credit_card_id' => $selectedCreditCard,
            'credit_card_holder' => $credit_card_holder,
        ];
        
        foreach($orderData['products'] as $productData) {
            $product = Product::find($productData['product_id']);
            
            if (!$product) {
                return new \Exception('Ürün bulunamadı, Sipariş oluşturulamadı!');
            }
        }

        if($campaign_id && $discount > 0){
            $appliedCampaign = Campaign::find($campaign_id);
            if($appliedCampaign){
                $campaignManager->userEligible($appliedCampaign);
                $campaignManager->decreaseUsageLimit($appliedCampaign);
            }
        }
        $exOrder = Order::create([
            'Bag_User_id' => $user->id,
            'user_id' => $user->id,
            'credit_card_id' => $selectedCreditCard,
            'card_holder_name' => $credit_card_holder,
            'price' => $totally,
            'cargo_price' => $cargo_price,
            'discount' => $discount,
            'campaign_id' => $campaign_id,
            'campaign_info' => $campaign_info,
            'campaing_price' => $total + $cargo_price - $discount,
            'status' => 'Başarısız Ödeme',
        ]);
        foreach($orderData['products'] as $productData) {
            OrderItem::create([
                'order_id' => $exOrder->id,
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'price' => $productData['price']
            ]);
        }
        if(!$exOrder){
            return new \Exception('Sipariş oluşturulamadı!');
        }

        $creditCard = CreditCard::find($selectedCreditCard);
        $iyzicoService = new IyzicoPaymentService();
        $paymentResult = $iyzicoService->processPayment($exOrder, $creditCard, $totally);
                
        if ($paymentResult['success']) {
            $exOrder->forcedelete();
            $job = new CreateOrderJob($orderData);
            $job->handle();
            return $paymentResult;
        } else {
            try {
                $exOrder->delete();
            } catch (\Exception $e) {
                \Log::error('Error deleting order: ' . $e->getMessage());
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
        return Order::where('Bag_User_id', $user)
                    ->where('id',$id)
                    ->first();
    }

}