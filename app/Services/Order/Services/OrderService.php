<?php

namespace App\Services\Order\Services;

use App\Services\Order\Contracts\OrderServiceInterface;
use App\Services\Order\Contracts\CalculationInterface;
use App\Services\Order\Contracts\PaymentInterface;
use App\Services\Order\Contracts\InventoryInterface;
use App\Exceptions\OrderCreationException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\CreditCard\CreditCardRepositoryInterface;
class OrderService implements OrderServiceInterface
{
    protected $calculationService;
    protected $paymentService;
    protected $inventoryService;
    protected $creditCardRepository;
    public function __construct(
        CalculationInterface $calculationService,
        PaymentInterface $paymentService,
        InventoryInterface $inventoryService,
        CreditCardRepositoryInterface $creditCardRepository
    )
    {
        $this->calculationService = $calculationService;
        $this->paymentService = $paymentService;
        $this->inventoryService = $inventoryService;
        $this->creditCardRepository = $creditCardRepository;
    }

    public function createOrder($user, $products, $campaignManager, $selectedCreditCard): array
    {
        DB::beginTransaction();
        
        try {
            
            $this->inventoryService->checkStock($products);
            
            
            $campaigns = Campaign::where('is_active', 1)->get();
            $total = $this->calculationService->calculateTotal($products);
            $cargoPrice = $this->calculationService->calculateCargoPrice($total);
            $discountData = $this->calculationService->calculateDiscount($products, $campaigns, $campaignManager);
            $finalPrice = $total + $cargoPrice - $discountData['discount'];
            $discountRate = $this->calculationService->calculateDiscountRate($total, $finalPrice);
            
            $order = $this->createOrderRecord($user, $selectedCreditCard, [
                'total' => $total,
                'cargo_price' => $cargoPrice,
                'discount' => $discountData['discount'],
                'campaign_id' => $discountData['campaign_id'],
                'campaign_info' => $discountData['description'],
                'final_price' => $finalPrice
            ]);
            
            $this->createOrderItems($order, $products, $discountRate);
            
            if ($discountData['campaign_id'] && $discountData['discount'] > 0) {
                $this->applyCampaign($discountData['campaign_id'], $campaignManager);
            }
            
            $creditCard = $this->creditCardRepository->getCreditCardById($selectedCreditCard);
            $paymentResult = $this->paymentService->processPayment($order, $creditCard, $finalPrice);
            
            if ($paymentResult['success']) {
                $this->paymentService->handlePaymentSuccess($order, $paymentResult);
                
                $this->inventoryService->updateInventory($products);
                
                DB::commit();
                
                return [
                    'success' => true,
                    'order_id' => $order->id,
                    'message' => 'Sipariş başarıyla oluşturuldu'
                ];
            } else {
                throw new OrderCreationException($paymentResult['error'], $paymentResult['error_code'] ?? null);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Order creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (isset($order)) {
                $order->delete();
            }
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => method_exists($e, 'getErrorCode') ? $e->getErrorCode() : null
            ];
        }
    }

    public function getOrder($userId, $orderId)
    {
        return Order::where('user_id', $userId)
                    ->where('id', $orderId)
                    ->first();
    }

    protected function createOrderRecord($user, $selectedCreditCard, array $product): Order
    {
        $creditCard = $this->creditCardRepository->getCreditCardById($selectedCreditCard);

        return Order::create([
            'bag_user_id' => $user->id,
            'user_id' => $user->id,
            'credit_card_id' => $selectedCreditCard,
            'card_holder_name' => $creditCard->card_holder_name,
            'order_price' => $product['total'],
            'order_price_cents' => (int)($product['total'] * 100),
            'cargo_price' => $product['cargo_price'],
            'cargo_price_cents' => (int)($product['cargo_price'] * 100),
            'discount' => $product['discount'],
            'discount_cents' => (int)($product['discount'] * 100),
            'campaign_id' => $product['campaign_id'],
            'campaign_info' => $product['campaign_info'],
            'campaign_price' => $product['total'] + $product['cargo_price'] - $product['discount'],
            'campaign_price_cents' => (int)(($product['total'] + $product['cargo_price'] - $product['discount']) * 100),
            'paid_price' => 0,
            'paid_price_cents' => 0,
            'currency' => 'TRY',
            'payment_id' => null,
            'conversation_id' => null,
            'payment_status' => 'failed',
            'status' => 'Başarısız Ödeme',
        ]);
    }

    protected function createOrderItems(Order $order, $products, float $discountRate) : void
    {
        foreach ($products as $product) {
            $paidPrice = round($product->product->list_price * $product->quantity * $discountRate, 2);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->product_id,
                'product_title' => $product->product->title,
                'product_category_title' => $product->product->category->category_title,
                'quantity' => $product->quantity,
                'refunded_quantity' => 0,
                'list_price' => $product->product->list_price,
                'list_price_cents' => (int)($product->product->list_price * 100),
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

    protected function applyCampaign($campaignId, $campaignManager) : void
    {
        $campaign = Campaign::find($campaignId);
        if($campaign){
            $campaignManager->userEligible($campaign);
            $campaignManager->decreaseUsageLimit($campaign);
        }    
    }
}