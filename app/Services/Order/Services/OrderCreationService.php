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

    public function createOrderRecord(User $user, int $selectedCreditCard, array $orderData): Order
    {
        $creditCard = $this->creditCardRepository->getCreditCardById($selectedCreditCard);

        return Order::create([
            'bag_user_id' => $user->id,
            'user_id' => $user->id,
            'credit_card_id' => $selectedCreditCard,
            'card_holder_name' => $creditCard->card_holder_name,
            'order_price' => $orderData['total'],
            'order_price_cents' => (int)($orderData['total'] * 100),
            'cargo_price' => $orderData['cargo_price'],
            'cargo_price_cents' => (int)($orderData['cargo_price'] * 100),
            'discount' => $orderData['discount'],
            'discount_cents' => (int)($orderData['discount'] * 100),
            'campaign_id' => $orderData['campaign_id'],
            'campaign_info' => $orderData['campaign_info'],
            'campaign_price' => $orderData['total'] + $orderData['cargo_price'] - $orderData['discount'],
            'campaign_price_cents' => (int)(($orderData['total'] + $orderData['cargo_price'] - $orderData['discount']) * 100),
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
        foreach ($products as $product) {
            $paidPrice = $product->product->list_price * $product->quantity;
            $discountAmount = 0;
            
            if($eligible_products && $this->isProductEligible($product, $eligible_products)){
                $discountItem = $perProductDiscount->first(function($item) use ($product) {
                    return $item['product']->id === $product->product_id;
                });
                
                if ($discountItem) {
                    $discountAmount = $discountItem['discount'];
                    $paidPrice = round($product->product->list_price * $product->quantity - $discountAmount, 2);
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
                'discount_price' => $discountAmount,
                'discount_price_cents' => (int)($discountAmount * 100),
                'paid_price' => $paidPrice,
                'paid_price_cents' => (int)($paidPrice * 100),
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
        
        // eligible_products integer ID'ler veya objeler içerebilir
        return $eligible_products->contains(function($item) use ($product) {
            // Eğer item bir integer ise (product ID)
            if (is_int($item) || is_string($item)) {
                return $item == $product->product_id;
            }
            
            // Eğer item bir obje ise (BagItem)
            if (is_object($item) && isset($item->product_id)) {
                return $item->product_id === $product->product_id;
            }
            
            return false;
        });
    }
}