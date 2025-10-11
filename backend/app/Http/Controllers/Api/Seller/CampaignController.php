<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Seller\CampaignService;
use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Http\Resources\Campaign\CampaignResource;

class CampaignController extends Controller
{

    public function __construct(
       private readonly CampaignService $campaignService, 
       private readonly StoreRepositoryInterface $storeRepository, 
    ) {
    }

    public function index()
    {
        $campaigns = $this->campaignService->getCampaigns();

        return CampaignResource::collection($campaigns);
    }

    public function store(CampaignStoreRequest $request)
    {
        $campaign = $this->campaignService->createCampaign($request->all());
            
        return new CampaignResource($campaign);
    }

    public function show($id)
    {

        $campaign = $this->campaignService->showCampaign($id);   

        return new CampaignResource($campaign);
    }

    public function update(CampaignUpdateRequest $request, $id)
    {            
        $campaign = $this->campaignService->updateCampaign($request->all(), $id);

        return new CampaignResource($campaign);
        
    }
    
    public function destroy($id)
    {
        $campaign = $this->campaignService->deleteCampaign($id); 

        return response()->json([
            'message' => 'Kampanya başarıyla silindi',
        ]);
    }
}
