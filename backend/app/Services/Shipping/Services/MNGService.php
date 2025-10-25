<?php

namespace App\Services\Shipping\Services;

use App\Services\Shipping\Contracts\ShippingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MNGService implements ShippingServiceInterface
{
    protected $secret_key;
    protected $api_key;
    protected $base_url;
    protected $testMode;

    public function __construct()
    {
        $this->secret_key = config('services.mng.secret_key');
        $this->api_key = config('services.mng.api_key');
        $this->base_url = rtrim(config('services.mng.api_base'), '/');
        $this->testMode = config('services.mng.test_mode');
    }

    public function createShipment(array $data): array
    {
        try {
            //entegrasyon yarım kaldığı için mock 
            if ($this->testMode) {
                return $this->createMockShipment($data);
            }

            $token = $this->getToken();
            if (!$token) {
                Log::warning('MNG Token alınamadı');
                return [
                    'success' => false,
                    'error' => 'Token alınamadı'
                ];
            }

            return $this->createRealShipment($data, $token);

        } catch (\Exception $e) {
            Log::error('MNG Kargo API Hatası: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    private function getToken(): ?string
    {
        try {
            $jwt = cache('mng_jwt');
            if ($jwt) return $jwt;

            $payload = [
                'userName' => '312947702',
                'password' => 'ABCD1234',
                'identityType' => 1,
            ];

            $response = Http::timeout(30)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-IBM-Client-Id' => $this->api_key,
                'X-IBM-Client-Secret' => $this->secret_key,
            ])->post($this->base_url . '/token', $payload);

            Log::info('MNG Token Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $jwt = $data['jwt'] ?? null;
                
                if ($jwt) {
                    cache()->put('mng_jwt', $jwt, now()->addMinutes(50));
                    return $jwt;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('MNG Token Exception: ' . $e->getMessage());
            return null;
        }
    }

    private function createRealShipment(array $data, string $token): array
    {
        $payload = [
            'order' => [
                'referenceId' => (string)$data['order_item_id'],
                'barcode' => (string)$data['order_item_id'],
                'billOfLandingId' => 'INV-' . $data['order_item_id'],
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
            'orderPieceList' => [[
                'barcode' => (string)$data['order_item_id'] . '_P1',
                'desi' => 2,
                'kg' => 1,
                'content' => $data['product_title'] ?? 'Ürün'
            ]],
            'recipient' => [
                'customerId' => 0,
                'refCustomerId' => '',
                'cityCode' => 34,
                'cityName' => $data['city'] ?? 'İstanbul',
                'districtCode' => 1527,
                'districtName' => $data['district'] ?? 'Kadıköy',
                'address' => $data['address'] ?? 'Test Adres',
                'bussinessPhoneNumber' => '',
                'email' => $data['email'] ?? 'test@example.com',
                'taxOffice' => '',
                'taxNumber' => '11111111111',
                'fullName' => $data['username'] ?? 'Test Kullanıcı',
                'homePhoneNumber' => '',
                'mobilePhoneNumber' => $data['phone'] ?? '5555555555'
            ]
        ];

        $response = Http::timeout(30)->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'x-api-version' => '1.0',
            'X-IBM-Client-Id' => $this->api_key,
            'X-IBM-Client-Secret' => $this->secret_key,
            'Authorization' => 'Bearer ' . $token
        ])->post($this->base_url . '/standardcmdapi/createOrder', $payload);

        Log::info('MNG CreateOrder Response', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            return [
                'success' => true,
                'tracking_number' => $responseData['trackingNumber'] ?? uniqid('MNG'),
                'shipping_company' => 'MNG Kargo',
                'shipping_status' => 'pending',
                'estimated_delivery_date' => null,
                'shipping_notes' => 'MNG Kargo ile oluşturuldu',
            ];
        }
        return [
            'success' => false,
            'error' => 'API Error: ' . $response->status()
        ];
    }

    private function createMockShipment(array $data): array
    {
        Log::info('MNG Mock Kargo Oluşturuluyor', $data);
        
        $trackingNumber = 'MNG' . time() . rand(1000, 9999);
        
        return [
            'success' => true,
            'tracking_number' => $trackingNumber,
            'shipping_company' => 'MNG Kargo (Test)',
            'shipping_status' => 'pending',
            'estimated_delivery_date' => now()->addDays(3)->format('Y-m-d'),
            'shipping_notes' => 'Test modu - gerçek kargo oluşturulmadı',
            'mock_data' => [
                'order_id' => $data['order_item_id'] ?? 'N/A',
                'recipient' => $data['username'] ?? 'Test Kullanıcı',
                'city' => $data['city'] ?? 'İstanbul',
                'created_at' => now()->format('Y-m-d H:i:s')
            ]
        ];
    }

}