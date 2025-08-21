<?php

namespace App\Services\Seller;

use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;

class CampaignService
{
    protected $storeRepository;
    protected $campaignRepository;
    public function __construct(StoreRepositoryInterface $storeRepository, CampaignRepositoryInterface $campaignRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function getCampaigns($sellerId)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->campaignRepository->getCampaignsByStoreId($store->id);
        
    }

    public function createCampaign($sellerId, array $campaignData)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        $campaignData['store_id'] = $store->id;
        $campaignData['store_name'] = $store->name;

        $campaign = $this->campaignRepository->createCampaign($campaignData);
        
        $this->createCampaignConditions($campaign, $campaignData['conditions'] ?? []);
        $this->createCampaignDiscount($campaign, $campaignData);

        return $campaign;
        
    }
    public function showCampaign($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);

        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);

        if(!$campaign){
            throw new \Exception('Kampanya bulunamadı');
        }
        return $campaign;

    }
    public function updateCampaign($sellerId, array $campaignData, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);

        if(!$campaign){
            throw new \Exception('Kampanya bulunamadı');
        }
        
        $updateResult = $this->campaignRepository->updateCampaign($campaignData, $id);
        if(!$updateResult){
            throw new \Exception('Kampanya güncellenemedi');
        }
        
        $conditions = $campaignData['existing_conditions'] ?? $campaignData['conditions'] ?? [];
        
        $this->updateCampaignConditions($campaign, $conditions);
        $this->updateCampaignDiscount($campaign, $campaignData);
        
        return $this->campaignRepository->getCampaignByStoreId($store->id, $id);
    }

    public function deleteCampaign($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);
        if(!$campaign){
            throw new \Exception('Kampanya bulunamadı');
        }
        $campaign->delete();
        return $campaign;
    }

    private function createCampaignConditions($campaign, array $conditions)
    {
        foreach ($conditions as $condition) {
            $campaign->conditions()->create([
                'condition_type' => $condition['condition_type'],
                'condition_value' => $condition['condition_value'],
                'operator' => $condition['operator']
            ]);
        }
    }

    private function createCampaignDiscount($campaign, array $campaignData)
    {
        // Tek indirim sistemi - tip bazlı işleme
        $type = $campaignData['type'] ?? null;
        $discountValue = $campaignData['discount_value'] ?? null;
        
        if (!$type || !$discountValue) {
            return;
        }
        
        // discount_value'yu doğru formatta kaydet
        $formattedValue = $this->formatDiscountValue($discountValue, $type);
        
        $campaign->discounts()->create([
            'discount_type' => $type,
            'discount_value' => $formattedValue,
        ]);
    }
    private function updateCampaignConditions($campaign, array $conditions)
    {
        foreach ($conditions as $condition) {
            if (isset($condition['id'])) {
                $existingCondition = $campaign->conditions()->find($condition['id']);
                if ($existingCondition) {
                    $existingCondition->update([
                        'condition_type' => $condition['condition_type'],
                        'condition_value' => $condition['condition_value'],
                        'operator' => $condition['operator']
                    ]);
                }
            }
        }
    }
    private function updateCampaignDiscount($campaign, array $campaignData)
    {

        
        // Tek indirim sistemi - tip bazlı işleme
        $type = $campaignData['type'] ?? null;
        $discountValue = $campaignData['discount_value'] ?? null;
        
        if (!$type || !$discountValue) {
            return;
        }
        
        // Mevcut indirimi bul veya oluştur
        $existingDiscount = $campaign->discounts()->first();
        
        if ($existingDiscount) {
            // Mevcut indirimi güncelle
            $formattedValue = $this->formatDiscountValue($discountValue, $type);
            
            $existingDiscount->update([
                'discount_type' => $type,
                'discount_value' => $formattedValue,
            ]);
        } else {
            // Yeni indirim oluştur
            $this->createCampaignDiscount($campaign, $campaignData);
        }
    }
    
    private function formatDiscountValue($value, $type)
    {
    
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded; 
            }
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        return $value;
    }

    private function formatValue($value)
    {
        
        if (is_string($value) && json_decode($value) !== null) {
            return $value;
        }
        
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


}
