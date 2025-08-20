<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Seller\CampaignService;
use App\Http\Requests\Seller\Campaign\CampaignStoreRequest;
use App\Http\Requests\Seller\Campaign\CampaignUpdateRequest;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;

class CampaignController extends Controller
{
    protected $campaignService;
    protected $storeRepository;
    public function __construct(CampaignService $campaignService, StoreRepositoryInterface $storeRepository)
    {
        $this->campaignService = $campaignService;
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        $campaigns = $this->campaignService->indexCampaign();
        if(!$campaigns){
            return ResponseHelper::notFound('Kampanya bulunamadı');
        }
        return ResponseHelper::success('Kampanyalar listelendi',$campaigns);
    }

    public function store(CampaignStoreRequest $request)
    {
        try{
        $campaigns = $this->campaignService->createCampaign($request);
        if(!$campaigns){
            return ResponseHelper::error('Kampanya oluşturulamadı');
        }
        return ResponseHelper::success('Kampanya başarıyla oluşturuldu',$campaigns);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Kampanya oluşturulamadı');
        }
    }

    public function show($id)
    {
        return $this->campaignService->showCampaign($id);   
        
    }

    public function update(CampaignUpdateRequest $request, $id)
    {
        return $this->campaignService->updateCampaign($request, $id);
        
    }
    
    public function destroy($id)
    {
        return $this->campaignService->deleteCampaign($id);
        
    }
    
    

    
}
