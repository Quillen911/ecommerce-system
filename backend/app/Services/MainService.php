<?php

namespace App\Services;

use App\Services\Search\ElasticsearchService;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Traits\GetUser;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;
use App\Repositories\Contracts\Attribute\AttributeRepositoryInterface;
use App\Repositories\Contracts\AttributeOptions\AttributeOptionsRepositoryInterface;

class MainService
{
    use GetUser;
    protected $elasticSearch;
    protected $productRepository;
    protected $categoryRepository;
    protected $authenticationRepository;
    protected $campaignRepository;
    protected $attributeRepository;
    protected $attributeOptionsRepository;
    public function __construct(
        ElasticsearchService $elasticSearch, 
        ProductRepositoryInterface $productRepository, 
        CategoryRepositoryInterface $categoryRepository,
        AuthenticationRepositoryInterface $authenticationRepository,
        CampaignRepositoryInterface $campaignRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeOptionsRepositoryInterface $attributeOptionsRepository
    )
    {
        $this->elasticSearch = $elasticSearch;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authenticationRepository = $authenticationRepository;
        $this->campaignRepository = $campaignRepository;
        $this->attributeRepository = $attributeRepository;
        $this->attributeOptionsRepository = $attributeOptionsRepository;
    }

    public function getProducts()
    {

        return $this->productRepository->getProductsWithCategory();
    }
    
    public function getCategories()
    {

        return $this->categoryRepository->getAllCategories();
    }
    public function getCategory($category_slug)
    {

        return $this->categoryRepository->getCategoryBySlug($category_slug);
    }
    public function getProduct($id)
    {

        return $this->productRepository->getProductWithCategory($id);
    }

    public function getCampaigns()
    {

        $campaigns = $this->campaignRepository->getActiveCampaigns();
        return $campaigns;
    }

    public function getAttributes()
    {

        return $this->attributeRepository->getAllAttributes();
    }

    public function getAttributeOptions()
    {

        return $this->attributeOptionsRepository->getAllAttributeOptions();
    }

}