<?php

namespace App\Services\Seller;

use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;

class CampaignService
{
    public function __construct(
        private readonly StoreRepositoryInterface $storeRepository, 
        private readonly CampaignRepositoryInterface $campaignRepository,
        private readonly AuthenticationRepositoryInterface $authenticationRepository

    ) {    
    }

    public function getCampaigns()
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Seller bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        return $this->campaignRepository->getCampaignsByStoreId($store->id);
        
    }

    public function createCampaign(array $campaignData)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Seller bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        $productIds = $campaignData['product_ids'] ?? [];
        $categoryIds = $campaignData['category_ids'] ?? [];
        unset($campaignData['product_ids'], $campaignData['category_ids']);
        $campaignData['store_id'] = $store->id;
        $campaign = $this->campaignRepository->createCampaign($campaignData);
        if ($productIds) {
            $campaign->products()->sync($productIds);
        }
    
        if ($categoryIds) {
            $campaign->campaignCategories()->delete();
            $campaign->campaignCategories()->createMany(
                collect($categoryIds)->map(fn ($id) => ['category_id' => $id])->all()
            );
        }
        return $campaign->load('campaignProducts', 'campaignCategories'); 
    }

    public function showCampaign($id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Seller bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);

        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);

        if(!$campaign){
            throw new \Exception('Kampanya bulunamadı');
        }
        return $campaign;

    }
    
    public function updateCampaign(array $campaignData, $id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if (!$seller) {
            throw new \Exception('Seller bulunamadı');
        }
    
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }
    
        $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);
        if (!$campaign) {
            throw new \Exception('Kampanya bulunamadı');
        }
    
        $productIds = $campaignData['product_ids'] ?? null;
        $categoryIds = $campaignData['category_ids'] ?? null;
    
        unset($campaignData['product_ids'], $campaignData['category_ids']);
        
        $updatedCampaign = $this->campaignRepository->updateCampaign($campaignData, $id);
        if(!$updatedCampaign){   
           throw new \Exception('Kampanya güncellenemedi');
        }
        
        if ($productIds !== null) {
            $updatedCampaign->campaignProducts()->delete();
            $updatedCampaign->campaignProducts()->createMany(
                collect($productIds)->map(fn ($prodId) => ['product_id' => $prodId])->all()
            );
        }
    
        if ($categoryIds !== null) {
            $updatedCampaign->campaignCategories()->delete();
            $updatedCampaign->campaignCategories()->createMany(
                collect($categoryIds)->map(fn ($catId) => ['category_id' => $catId])->all()
            );
        }
    
        return $updatedCampaign->load('campaignProducts', 'campaignCategories');
    }
    

    public function deleteCampaign($id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Seller bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $campaign = $this->campaignRepository->getCampaignByStoreId($store->id, $id);
        if(!$campaign){
            throw new \Exception('Kampanya bulunamadı');
        }
        $campaign->delete();
        return true;
    }
}
