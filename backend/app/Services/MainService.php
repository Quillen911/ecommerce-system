<?php

namespace App\Services;

use App\Services\Search\ElasticsearchService;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Campaign\CampaignRepositoryInterface;
use App\Repositories\Contracts\Product\ProductVariantRepositoryInterface;
class MainService
{
    protected $elasticSearch;
    protected $productRepository;
    protected $categoryRepository;
    protected $campaignRepository;
    protected $productVariantRepository;
    public function __construct(
        ElasticsearchService $elasticSearch, 
        ProductRepositoryInterface $productRepository, 
        CategoryRepositoryInterface $categoryRepository,
        CampaignRepositoryInterface $campaignRepository,
        ProductVariantRepositoryInterface $productVariantRepository
    )
    {
        $this->elasticSearch = $elasticSearch;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->campaignRepository = $campaignRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    public function getProducts()
    {
        return $this->productRepository->getProductsWithCategory();
    }

    public function getProductsPopularVariants()
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

    public function getCampaigns()
    {
        return $this->campaignRepository->getActiveCampaigns();
    }

}