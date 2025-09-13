<?php

namespace App\Services;

use App\Services\Search\ElasticsearchService;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Traits\GetUser;

class MainService
{
    use GetUser;
    protected $elasticSearch;
    protected $productRepository;
    protected $categoryRepository;
    protected $authenticationRepository;
    
    public function __construct(
        ElasticsearchService $elasticSearch, 
        ProductRepositoryInterface $productRepository, 
        CategoryRepositoryInterface $categoryRepository,
        AuthenticationRepositoryInterface $authenticationRepository
    )
    {
        $this->elasticSearch = $elasticSearch;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authenticationRepository = $authenticationRepository;
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

}