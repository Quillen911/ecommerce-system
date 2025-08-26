<?php

namespace App\Services\Shipping\Services;

use App\Services\Shipping\Contracts\ShippingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class MNGService implements ShippingServiceInterface
{
    protected $clientSecret;
    protected $clientId;
    protected $endpoint;
    protected $testMode;
    public function __construct(){
        $this->clientSecret = config('services.mng.client_secret');
        $this->clientId = config('services.mng.client_id');
        $this->endpoint = config('services.mng.endpoint');
        $this->testMode = config('services.mng.test_mode');
    }
    public function createShipment(array $data): array
    {
        try{

            // MNG Swagger API şemasına uygun payload
            $payload = [
                'order' => [
                    'referenceId' => (string)$data['order_item_id'],
                    'barcode' => (string)$data['order_item_id'],
                    'billOfLandingId' => 'Irsaliye-' . $data['order_item_id'],
                    'isCOD' => 0,
                    'codAmount' => 0,
                    'shipmentServiceType' => 1,
                    'packagingType' => 4,
                    'content' => $data['product_title'] ?? 'Ürün',
                    'smsPreference1' => 1,
                    'smsPreference2' => 0,
                    'smsPreference3' => 0,
                    'paymentType' => 1,
                    'deliveryType' => 1,
                    'description' => 'E-ticaret siparişi'
                ],
                'orderPieceList' => [
                    [
                        'barcode' => (string)$data['order_item_id'] . '_PARCA1',
                        'desi' => 2,
                        'kg' => 1,
                        'content' => $data['product_title'] ?? 'Ürün'
                    ]
                ],
                'recipient' => [
                    'customerId' => 0,
                    'refCustomerId' => '',
                    'cityCode' => 34, // İstanbul
                    'districtCode' => 1527, // Kadıköy
                    'address' => $data['address'] ?? 'Test Adres',
                    'bussinessPhoneNumber' => '',
                    'email' => $data['email'] ?? 'test@example.com',
                    'taxOffice' => '',
                    'taxNumber' => '',
                    'fullName' => $data['username'] ?? 'Test Kullanıcı',
                    'homePhoneNumber' => '',
                    'mobilePhoneNumber' => $data['phone'] ?? '5555555555'
                ]
            ];

            // MNG Kargo API için doğru endpoint ve payload yapısı
            /*if ($this->testMode) {
                // Test modunda mock response döndür
                return $this->mockResponse($data);
            } */

            // MNG API - Üç zorunlu header
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-api-version' => '1.0',
                'X-IBM-Client-Id' => $this->clientId,
                'X-IBM-Client-Secret' => $this->clientSecret,
                'Authorization' => 'Bearer ' . $this->clientSecret
            ])
            ->post($this->endpoint . '/standardcmdapi/createOrder', $payload);

            Log::info('MNG API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if($response->successful()){
                $data = $response->json();
                return [
                    'success' => true,
                    'tracking_number' => $data['trackingNumber'] ?? uniqid('MNG'),
                    'shipping_company' => 'MNG',
                    'shipping_status' => $data['status'] ?? null,
                    'estimated_delivery_date' => $data['estimatedDelivery'] ?? null,
                    'shipping_notes' => $data['notes'] ?? null,
                ];
            }
            return [
                'success' => false,
                'error' => 'API yanıtı başarısız: ' . $response->status()
            ];

        }catch(\Exception $e){
            Log::error('MNG Kargo API Hatası: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test modunda kullanılacak mock response
     */
    private function mockResponse(array $data): array
    {
        Log::info('MNG Kargo Mock Response', ['data' => $data]);
        
        // Gerçekçi bir tracking number oluştur
        $trackingNumber = 'MNG' . date('Ymd') . rand(100000, 999999);
        
        return [
            'success' => true,
            'tracking_number' => $trackingNumber,
            'shipping_company' => 'MNG Kargo',
            'shipping_status' => 'preparing',
            'estimated_delivery_date' => now()->addDays(3)->format('Y-m-d'),
            'shipping_notes' => 'Kargo hazırlanıyor. Test modu aktif.',
        ];
    }
}