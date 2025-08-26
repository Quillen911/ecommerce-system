<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\Services\Order\Services\OrderService;
use App\Services\Order\Contracts\CalculationInterface;
use App\Services\Order\Contracts\PaymentInterface;
use App\Services\Order\Contracts\InventoryInterface;
use App\Repositories\Contracts\CreditCard\CreditCardRepositoryInterface;
use App\Services\Order\Contracts\OrderCreationInterface;

class OrderServiceTest extends TestCase
{

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_calculate_order_data()
    {
        // Mock'ları oluştur
        $calculationService = Mockery::mock(CalculationInterface::class);
        $paymentService = Mockery::mock(PaymentInterface::class);
        $inventoryService = Mockery::mock(InventoryInterface::class);
        $creditCardRepository = Mockery::mock(CreditCardRepositoryInterface::class);
        $orderCreationService = Mockery::mock(OrderCreationInterface::class);

        $orderItems = collect([
            (object)['quantity' => 1, 'product' => (object)['list_price' => 100]],
            (object)['quantity' => 2, 'product' => (object)['list_price' => 150]],
        ]);
        
        $campaignManager = Mockery::mock('CampaignManager');
        
        $expectedTotal = 400;
        $expectedCargoPrice = 50;
        $expectedDiscount = 20;
        
        // Mock beklentileri
        $calculationService->shouldReceive('calculateTotal')
            ->once()
            ->with($orderItems)
            ->andReturn($expectedTotal);
            
        $calculationService->shouldReceive('calculateCargoPrice')
            ->once()
            ->with($expectedTotal)
            ->andReturn($expectedCargoPrice);
            
        $calculationService->shouldReceive('calculateDiscount')
            ->once()
            ->andReturn([
                'discount' => $expectedDiscount,
                'campaign_id' => 1,
                'description' => 'Test kampanya'
            ]);

        // Campaign model'ini mock'la
        \Mockery::mock('alias:App\Models\Campaign')
            ->shouldReceive('where')
            ->with('is_active', 1)
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn(collect([]));

        $orderService = new OrderService(
            $calculationService, 
            $paymentService, 
            $inventoryService, 
            $creditCardRepository, 
            $orderCreationService
        );

        $result = $orderService->calculateOrderData($orderItems, $campaignManager);
        
        $this->assertEquals($expectedTotal, $result['total']);
        $this->assertEquals($expectedCargoPrice, $result['cargo_price']);
        $this->assertEquals($expectedDiscount, $result['discount']);
        dd($result);
    }
        
    
}
