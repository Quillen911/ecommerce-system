<?php

namespace App\Services\Campaigns\Seller;

use App\Models\Campaign;
use App\Models\CampaignUserUsage;
use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Models\Store;
class CampaignService
{
    public function indexCampaign($storeId)
    {
        $campaigns = Campaign::where('store_id', $storeId)->orderBy('id')->get();
        return $campaigns;
    }
    public function createCampaign(CampaignStoreRequest $request)
    {
        try{

            $seller = auth('seller_web')->user();
            $store = Store::where('seller_id', $seller->id)->first();
            $campaignData = $request->all();
            $campaignData['store_id'] = $store->id;
            $campaignData['store_name'] = $store->name;
            $campaign = Campaign::create($campaignData);
            
            
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
            $campaign = Campaign::with(['conditions', 'discounts'])->findOrFail($id);
            return $campaign;
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
    public function updateCampaign(CampaignUpdateRequest $request, $id)
    {
        try {
            $campaign = Campaign::with(['conditions', 'discounts'])->findOrFail($id);
            $campaign->update($request->all());
            
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
            return $campaign;
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function deleteCampaign($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->delete();
            return $campaign;
        } catch (ModelNotFoundException $e) {
            return null;
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
