<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use App\Services\Search\ElasticsearchService;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;

class MainService
{
    protected $elasticSearch;
    protected $productRepository;
    protected $categoryRepository;
    public function __construct(ElasticsearchService $elasticSearch, ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->elasticSearch = $elasticSearch;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getProducts()
    {
        return $this->productRepository->getProductsWithCategory();
    }
    
    public function getCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }
    public function getProduct($id)
    {
        return $this->productRepository->getProductWithCategory($id);
    }

}