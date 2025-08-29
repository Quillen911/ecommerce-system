<?php

namespace App\Services\Order\Services;

use App\Repositories\Contracts\CreditCard\CreditCardRepositoryInterface;
use App\Services\Order\Contracts\OrderServiceInterface;
use App\Services\Order\Contracts\OrderCreationInterface;
use App\Services\Order\Contracts\CalculationInterface;
use App\Services\Order\Contracts\PaymentInterface;
use App\Services\Order\Contracts\InventoryInterface;
use App\Exceptions\OrderCreationException;
use App\Models\Campaign;
use App\Notifications\OrderCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\GetUser;
use App\Jobs\SendOrderNotification;

class OrderService implements OrderServiceInterface
{
    use GetUser;
    protected $calculationService;
    protected $paymentService;
    protected $inventoryService;
    protected $creditCardRepository;
    protected $orderCreationService;
    public function __construct(

        CalculationInterface $calculationService,
        PaymentInterface $paymentService,
        InventoryInterface $inventoryService,
        CreditCardRepositoryInterface $creditCardRepository,
        OrderCreationInterface $orderCreationService
    )
    {
        $this->calculationService = $calculationService;
        $this->paymentService = $paymentService;
        $this->inventoryService = $inventoryService;
        $this->creditCardRepository = $creditCardRepository;
        $this->orderCreationService = $orderCreationService;
    }

    public function createOrder($user, $products, $campaignManager, $selectedCreditCard, $tempCardData = null, $saveNewCard = false): array
    {
        DB::beginTransaction();
        
        try {
            $this->inventoryService->checkStock($products);
            
            $orderData = $this->calculateOrderData($products, $campaignManager);
            
            // Yeni kart için geçici ID kullan
            $creditCardIdForOrder = $selectedCreditCard === 'new_card' ? null : $selectedCreditCard;
            
            $order = $this->orderCreationService->createOrderRecord($user, $creditCardIdForOrder, $orderData);
            $this->orderCreationService->createOrderItems($order, $products, $orderData['eligible_products'], $orderData['per_product_discount']);
            
            if ($orderData['campaign_id'] && $orderData['discount'] > 0) {
                $this->orderCreationService->applyCampaign($orderData['campaign_id'], $campaignManager);
            }

            $paymentResult = $this->processPayment($order, $selectedCreditCard, $orderData['final_price'], $tempCardData, $saveNewCard);
            
            
            if ($paymentResult['success']) {
                $this->inventoryService->updateInventory($products);
                
                DB::commit();

                SendOrderNotification::dispatch($order, $user)->onQueue('notifications');
                return [
                    'success' => true,
                    'order_id' => $order->id,
                    'message' => 'Sipariş başarıyla oluşturuldu'
                ];
            } else {
                $this->paymentService->handlePaymentFailed($order, $paymentResult['error'], $paymentResult['error_code'] ?? null);

                throw new OrderCreationException($paymentResult['error'], $paymentResult['error_code'] ?? null);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleOrderCreationError($e, $user, $order ?? null);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => method_exists($e, 'getErrorCode') ? $e->getErrorCode() : null
            ];
        }
    }

    public function calculateOrderData($products, $campaignManager): array
    {
        $campaigns = Campaign::where('is_active', 1)->get();
        $discountData = $this->calculationService->calculateDiscount($products, $campaigns, $campaignManager);
        $total = $this->calculationService->calculateTotal($products);
        $cargoPrice = $this->calculationService->calculateCargoPrice($total);
        $eligible_products = $discountData['eligible_products'] ?? collect();
        $finalPrice = $total + $cargoPrice - $discountData['discount'];
        
        $perProductDiscount = $discountData['per_product_discount'] ?? collect();

        return [
            'total' => $total,
            'cargo_price' => $cargoPrice,
            'discount' => $discountData['discount'],
            'campaign_id' => $discountData['campaign_id'],
            'campaign_info' => $discountData['description'],
            'final_price' => $finalPrice,
            'eligible_products' => $eligible_products,
            'per_product_discount' => $perProductDiscount
        ];
    }

    protected function processPayment($order, $selectedCreditCard, $finalPrice, $tempCardData = null, $saveNewCard = false): array
    {
        if ($selectedCreditCard === 'new_card') {
            // Yeni kart ile ödeme - önce kartı oluştur
            $paymentResult = $this->processNewCardPayment($order, $tempCardData, $finalPrice, $saveNewCard);
        } else {
            // Mevcut kart ile ödeme
            $creditCard = $this->creditCardRepository->getCreditCardById($selectedCreditCard);
            $paymentResult = $this->paymentService->processPayment($order, $creditCard, $finalPrice, $tempCardData);
        }
        
        if ($paymentResult['success']) {
            $this->paymentService->handlePaymentSuccess($order, $paymentResult);
        }
        
        return $paymentResult;
    }
    
    protected function processNewCardPayment($order, $tempCardData, $finalPrice, $saveNewCard): array
    {
        try {
            // Geçici kart oluştur (sadece ödeme için)
            $tempCard = new \App\Models\CreditCard([
                'user_id' => $order->user_id,
                'name' => $tempCardData['card_name'],
                'last_four_digits' => substr($tempCardData['card_number'], -4),
                'expire_year' => $tempCardData['expire_year'],
                'expire_month' => $tempCardData['expire_month'],
                'card_type' => $this->detectCardType($tempCardData['card_number']),
                'card_holder_name' => $tempCardData['card_holder_name'],
                'is_active' => true
            ]);
            
            // Ödemeyi işle
            $paymentResult = $this->paymentService->processPayment($order, $tempCard, $finalPrice, [
                'card_number' => $tempCardData['card_number'],
                'cvv' => $tempCardData['cvv']
            ]);
            
            if ($paymentResult['success'] && $saveNewCard) {
                // Ödeme başarılı ve kart kaydedilmek isteniyor
                $savedCard = $this->creditCardRepository->createCreditCard([
                    'user_id' => $order->user_id,
                    'name' => $tempCardData['card_name'],
                    'last_four_digits' => substr($tempCardData['card_number'], -4),
                    'expire_year' => $tempCardData['expire_year'],
                    'expire_month' => $tempCardData['expire_month'],
                    'card_type' => $this->detectCardType($tempCardData['card_number']),
                    'card_holder_name' => $tempCardData['card_holder_name'],
                    'is_active' => true,
                    'iyzico_card_token' => $paymentResult['card_token'] ?? null,
                    'iyzico_card_user_key' => $paymentResult['card_user_key'] ?? null
                ]);
                
                // Order'ı kaydedilen kart ile güncelleyelim
                $order->update([
                    'credit_card_id' => $savedCard->id,
                    'card_holder_name' => $savedCard->card_holder_name
                ]);
                
                \Log::info('Yeni kart kaydedildi ve order güncellendi', [
                    'card_id' => $savedCard->id, 
                    'order_id' => $order->id,
                    'user_id' => $order->user_id
                ]);
            }
            
            return $paymentResult;
            
        } catch (\Exception $e) {
            \Log::error('Yeni kart ile ödeme hatası: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Ödeme işlemi başarısız oldu: ' . $e->getMessage()
            ];
        }
    }
    
    protected function detectCardType($cardNumber): string
    {
        $firstDigit = substr($cardNumber, 0, 1);
        if ($firstDigit === '4') return 'Visa';
        if ($firstDigit === '5') return 'Mastercard';
        if ($firstDigit === '3') return 'American Express';
        return 'Diğer';
    }

    protected function handleOrderCreationError(\Exception $e, $user, $order = null): void
    {
        Log::error('Order creation failed', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if ($order) {
            $order->delete();
        }
    }
    
    public function getOrder($user, $orderId)
    {
        return $this->orderCreationService->getOrder($user->id, $orderId);
    }
}