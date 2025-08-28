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

    public function createOrder($user, $products, $campaignManager, $selectedCreditCard): array
    {
        DB::beginTransaction();
        
        try {
            $this->inventoryService->checkStock($products);
            
            $orderData = $this->calculateOrderData($products, $campaignManager);
            
            $order = $this->orderCreationService->createOrderRecord($user, $selectedCreditCard, $orderData);
            $this->orderCreationService->createOrderItems($order, $products, $orderData['eligible_products'], $orderData['per_product_discount']);
            
            if ($orderData['campaign_id'] && $orderData['discount'] > 0) {
                $this->orderCreationService->applyCampaign($orderData['campaign_id'], $campaignManager);
            }

            $paymentResult = $this->processPayment($order, $selectedCreditCard, $orderData['final_price']);
            
            
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

    protected function processPayment($order, $selectedCreditCard, $finalPrice): array
    {
        $creditCard = $this->creditCardRepository->getCreditCardById($selectedCreditCard);
        $paymentResult = $this->paymentService->processPayment($order, $creditCard, $finalPrice);
        
        if ($paymentResult['success']) {
            $this->paymentService->handlePaymentSuccess($order, $paymentResult);
        }
        
        return $paymentResult;
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