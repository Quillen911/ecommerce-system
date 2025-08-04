<?php

namespace App\Services\Campaigns\Admin;

use App\Models\Campaign;
use App\Models\CampaignUserUsage;
use App\Http\Requests\Admin\Campaign\CampaignStoreRequest;
use App\Http\Requests\Admin\Campaign\CampaignUpdateRequest;
class CampaignService
{
    public function indexCampaign()
    {
        $campaigns = Campaign::orderBy('id')->get();
        return $campaigns;
    }
    public function createCampaign(CampaignStoreRequest $request)
    {

        $campaign = Campaign::create($request->only([
            'name', 'type', 'description', 'is_active', 'priority', 
            'usage_limit', 'usage_limit_for_user', 'starts_at', 'ends_at'
        ]));
        
        if ($request->has('conditions')) {
            foreach ($request->conditions as $condition) {
                $campaign->conditions()->create([
                    'condition_type' => $condition['condition_type'],
                    'condition_value' => $this->formatConditionValue($condition['condition_value']),
                    'operator' => $condition['operator']
                ]);
            }
        }
        if ($request->has('discounts')) {
            foreach ($request->discounts as $discount) {
                $campaign->discounts()->create([
                    'discount_type' => $discount['discount_type'],
                    'discount_value' => $this->formatDiscountValue($discount['discount_value']),
                ]);
            }
        }
        
        return $campaign;
    }
    public function showCampaign($id)
    {
        try {
            $campaign = Campaign::with(['conditions', 'discounts'])->findOrFail($id);
            return $campaign;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }
    public function updateCampaign(CampaignUpdateRequest $request, $id)
    {
        try {
            $campaign = Campaign::with(['conditions', 'discounts'])->findOrFail($id);
            $campaign->update($request->only([
                'name', 'type', 'description', 'is_active', 'priority', 
                'usage_limit', 'usage_limit_for_user', 'starts_at', 'ends_at'
            ]));
            
            if ($request->has('existing_conditions')) {
                foreach ($request->existing_conditions as $conditionId => $conditionData) {
                    $condition = $campaign->conditions()->find($conditionId);
                    if ($condition) {
                        $condition->update([
                            'condition_type' => $conditionData['condition_type'],
                            'condition_value' => $this->formatConditionValue($conditionData['condition_value']),
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
                            'discount_value' => $this->formatDiscountValue($discountData['discount_value']),
                        ]);
                    }
                }
            }
            return $campaign;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

    public function deleteCampaign($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->delete();
            return $campaign;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

    private function formatConditionValue($value)
    {

        if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
            return $value;
        }
        

        return json_encode($value);
    }

    private function formatDiscountValue($value)
    {

        if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
            return $value;
        }
        

        return json_encode($value);
    }

}