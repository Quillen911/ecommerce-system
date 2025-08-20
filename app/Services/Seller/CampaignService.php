<?php

namespace App\Services\Seller;

use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;
use App\Helpers\ResponseHelper;
class CampaignService
{
    protected $storeRepository;
    protected $campaignRepository;
    public function __construct(StoreRepositoryInterface $storeRepository, CampaignRepositoryInterface $campaignRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function indexCampaign()
    {
        $seller = auth('seller')->user();
        if(!$seller){
            return ['success' => false, 'message' => 'Lütfen giriş yapınız'];
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            return ['success' => false, 'message' => 'Mağaza bulunamadı'];
        }
        $campaigns = $this->campaignRepository->getCampaignsByStoreId($store->id);
        return ['success' => true, 'data' => $campaigns];
    }

    public function createCampaign(CampaignStoreRequest $request)
    {
        try{

            $seller = auth('seller')->user();
            $store = $this->storeRepository->getStoreBySellerId($seller->id);
            $campaignData = $request->all();
            $campaignData['store_id'] = $store->id;
            $campaignData['store_name'] = $store->name;
            $campaign = $this->campaignRepository->createCampaign($campaignData);
            
            
            if ($request->has('conditions')) {
                foreach ($request->conditions as $condition) {
                    $campaign->conditions()->create([
                        'condition_type' => $condition['condition_type'],
                        'condition_value' => $this->formatValue($condition['condition_value']),
                        'operator' => $condition['operator']
                    ]);
                }
            }
            if ($request->has('discounts')) {
                foreach ($request->discounts as $discount) {
                    $campaign->discounts()->create([
                        'discount_type' => $discount['discount_type'],
                        'discount_value' => $this->formatValue($discount['discount_value']),
                    ]);
                }
            }
            return $campaign;
        }
        catch(\Exception $e){
            Log::error('Campaign creation failed: ' . $e->getMessage());
            return null;
        }
    }
    public function showCampaign($id)
    {
        try {
            $seller = auth('seller')->user();
            if(!$seller){
                return ResponseHelper::error('Lütfen giriş yapınız');
            }
            $store = $this->storeRepository->getStoreBySellerId($seller->id);
            if(!$store){
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $campaigns = $this->campaignRepository->getCampaignByStoreId($store->id, $id);
            if(!$campaigns){
                return ResponseHelper::notFound('Kampanya bulunamadı');
            }
            return ResponseHelper::success('Kampanya detayı',$campaigns);

        } catch (\Exception $e) {
            return ResponseHelper::error('Kampanya bulunamadı');
        }
    }
    public function updateCampaign(CampaignUpdateRequest $request, $id)
    {
        try {
            $seller = auth('seller')->user();
            if(!$seller){
                return ResponseHelper::error('Lütfen giriş yapınız');
            }
            $store = $this->storeRepository->getStoreBySellerId($seller->id);
            if(!$store){
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);

            $campaignData = $request->all();
            $campaignData['store_id'] = $campaign->store_id;
            $campaignData['store_name'] = $campaign->store_name;
            $campaign = $this->campaignRepository->updateCampaign($campaignData, $id);
            
            if ($request->has('existing_conditions')) {
                foreach ($request->existing_conditions as $conditionId => $conditionData) {
                    $condition = $campaign->conditions()->find($conditionId);
                    if ($condition) {
                        $condition->update([
                            'condition_type' => $conditionData['condition_type'],
                            'condition_value' => $this->formatValue($conditionData['condition_value']),
                            'operator' => $conditionData['operator']
                        ]);
                    }
                }
                
            }
            
            if ($request->has('existing_discounts')) {
                foreach ($request->existing_discounts as $discountId => $discountData) {
                    $discount = $campaign->discounts()->find($discountId);
                    if ($discount) {
                        $discount->update([
                            'discount_type' => $discountData['discount_type'],
                            'discount_value' => $this->formatValue($discountData['discount_value']),
                        ]);
                    }
                }
            }
            return ResponseHelper::success('Kampanya başarıyla güncellendi',$campaignData);

        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error('Kampanya bulunamadı');
        }
    }

    public function deleteCampaign($id)
    {
        try {
            $seller = auth('seller')->user();
            if(!$seller){
                return ResponseHelper::error('Lütfen giriş yapınız');
            }
            $store = $this->storeRepository->getStoreBySellerId($seller->id);
            if(!$store){
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);
            $campaign->delete();
            return ResponseHelper::success('Kampanya başarıyla silindi',$campaign);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error('Kampanya bulunamadı');
        }
    }

    private function formatValue($value)
    {
        if (is_string($value) && (str_starts_with($value, '"') || str_starts_with($value, '{') || str_starts_with($value, '['))) {
            return $value;
        }
        
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if (is_numeric($value)) {
            return (string) $value;
        }
        
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


}
