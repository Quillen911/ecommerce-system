<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use App\Services\Search\ElasticsearchService;

class MainService
{
    protected $elasticSearch;
    public function __construct(ElasticsearchService $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    public function getProducts()
    {
        $page = request('page', 1);
        $products = Cache::remember("products.page.$page", 60, function () {
            return Product::with(['category'])->orderBy('id')->paginate(100);
        });
        return $products;
    }
    
    public function getCategories()
    {
        return Cache::remember('categories.all', 3600, function () {
            return Category::where('category_title')->get();
        });
    }
    public function getProduct($id)
    {
        return Product::with(['category'])->find($id);
    }

}