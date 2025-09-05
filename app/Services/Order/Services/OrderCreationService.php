<?php
namespace App\Services\Order\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Campaign;
use App\Models\User;
use App\Services\Order\Contracts\OrderCreationInterface;
use App\Repositories\Contracts\Order\OrderRepositoryInterface;
use App\Repositories\Contracts\CreditCard\CreditCardRepositoryInterface;
use App\Services\Campaigns\CampaignManager;
use Illuminate\Support\Collection;

class OrderCreationService implements OrderCreationInterface
{
    protected $creditCardRepository;
    protected $orderRepository;

    public function __construct(CreditCardRepositoryInterface $creditCardRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->creditCardRepository = $creditCardRepository;
        $this->orderRepository = $orderRepository;
    }

    public function createOrderRecord(User $user, ?int $selectedCreditCard, array $orderData): Order
    {
        $creditCard = $selectedCreditCard ? $this->creditCardRepository->getCreditCardById($selectedCreditCard) : null;

        return Order::create([
            'bag_user_id' => $user->id,
            'user_id' => $user->id,
            'credit_card_id' => $selectedCreditCard,
            'card_holder_name' => $creditCard?->card_holder_name ?? 'Yeni Kart',
            'order_price' => round($orderData['total'], 2),
            'order_price_cents' => (int)round($orderData['total'] * 100),
            'cargo_price' => round($orderData['cargo_price'], 2),
            'cargo_price_cents' => (int)round($orderData['cargo_price'] * 100),
            'discount' => round($orderData['discount'], 2),
            'discount_cents' => (int)round($orderData['discount'] * 100),
            'campaign_id' => $orderData['campaign_id'],
            'campaign_info' => $orderData['campaign_info'],
            'campaign_price' => round($orderData['final_price'], 2),
            'campaign_price_cents' => (int)round($orderData['final_price'] * 100),
            'paid_price' => 0,
            'paid_price_cents' => 0,
            'currency' => 'TRY',
            'payment_id' => null,
            'conversation_id' => null,
            'payment_status' => 'failed',
            'status' => 'Başarısız Ödeme',
        ]);
    }

    public function createOrderItems(Order $order, $products, $eligible_products, $perProductDiscount): void
    {
        $diffApplied = false;
        foreach ($products as $product) {
            $discountAmount = 0;
            $discountAmountCents = 0;
            $basePriceCents = (int)round($product->product->list_price * $product->quantity * 100);
            $paidPriceCents = $basePriceCents;
            $diff = 0;

            if ($eligible_products && $this->isProductEligible($product, $eligible_products)) {
                $discountItem = $perProductDiscount->first(function ($item) use ($product) {
                    return $item['product']->id === $product->product_id;
                });
        
                if ($discountItem) {
                    $discountAmount = $discountItem['discount'];
                    $discountAmountCents = (int)round($discountAmount * 100);
                    
                    $paidPriceCents = $basePriceCents - $discountAmountCents;
                
                    $calculatedPrice = ($product->product->list_price * $product->quantity - $discountAmount) * 100;
                    if($diffApplied == false){
                        $diff = (int)round($calculatedPrice) - $paidPriceCents;
                    }
                    
                    if($diff > 0){
                        $paidPriceCents = $paidPriceCents + $diff;
                        $diff = 0;
                        $diffApplied = true;
                    }
                }
            }
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->product_id,
                'product_title' => $product->product->title,
                'product_category_title' => $product->product->category->category_title,
                'quantity' => $product->quantity,
                'refunded_quantity' => 0,
                'list_price' => $product->product->list_price,
                'list_price_cents' => (int)($product->product->list_price * 100),
                'discount_price' => $discountAmountCents / 100,
                'discount_price_cents' => (int)$discountAmountCents,
                'paid_price' => $paidPriceCents / 100,
                'paid_price_cents' => (int)$paidPriceCents,
                'payment_transaction_id' => "",
                'refunded_price' => 0,
                'refunded_price_cents' => 0,
                'payment_status' => 'failed',
                'refunded_at' => null,
                'store_id' => $product->product->store_id,
                'store_name' => $product->product->store_name,
                'status' => 'Başarısız Ödeme',
            ]);
        }
    }
    
    public function applyCampaign(int $campaignId, CampaignManager $campaignManager): void
    {
        $campaign = Campaign::find($campaignId);
        if($campaign){
            $campaignManager->userEligible($campaign);
            $campaignManager->decreaseUsageLimit($campaign);
        }   
    }
    
    public function getOrder(int $userId, int $orderId): ?Order
    {
        return $this->orderRepository->getUserOrderById($userId, $orderId);
    }
    
    private function isProductEligible($product, $eligible_products): bool
    {
        if (empty($eligible_products)) {
            return false;
        }
        
        return $eligible_products->contains(function($item) use ($product) {
            if (is_int($item) || is_string($item)) {
                return $item == $product->product_id;
            }
            
            if (is_object($item) && isset($item->product_id)) {
                return $item->product_id === $product->product_id;
            }
            
            return false;
        });
    }
}