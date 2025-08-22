<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Helpers\ResponseHelper;
use App\Services\Search\ElasticsearchService;
use App\Http\Requests\FilterRequest;
use Illuminate\Support\Facades\Log;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Services\MainService;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
class MainController extends Controller
{
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    protected $mainService;
    protected $productRepository;
    protected $categoryRepository;
    protected $authenticationRepository;
    public function __construct(
        ElasticsearchService $elasticSearch, 
        ElasticSearchTypeService $elasticSearchTypeService, 
        ElasticSearchProductService $elasticSearchProductService, 
        MainService $mainService, 
        ProductRepositoryInterface $productRepository, 
        CategoryRepositoryInterface $categoryRepository,
        AuthenticationRepositoryInterface $authenticationRepository
    ) {
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->mainService = $mainService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authenticationRepository = $authenticationRepository;
    }

    public function index(Request $request)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        $products = $this->mainService->getProducts();
        $categories = $this->mainService->getCategories();
        return ResponseHelper::success('Ürünler', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $this->authenticationRepository->getUser();
        if(!$user){
            return ResponseHelper::notFound('Kullanıcı bulunamadı.');
        }
        $product = $this->mainService->getProduct($id);
        if(!$product){
            return ResponseHelper::notFound('Ürün bulunamadı.');
        }
        return ResponseHelper::success('Ürün', $product);
    }

    //elasticsearch
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $filters = $this->elasticSearchTypeService->filterType($request);
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        
        $data = $this->elasticSearchProductService->searchProducts($query, $filters, $sorting, $request->input('page', 1), $request->input('size', 12));
        
        if(!empty($data['products'])){
            return ResponseHelper::success('Ürünler Bulundu', [
                'total' => $data['results']['total'],
                'page' => $request->input('page', 1),
                'size' => $request->input('size', 12),
                'query' => $query ? $query : "null",
                'products' => $data['products'],
            ]);
        }
        return ResponseHelper::notFound('Ürün bulunamadı.', [
            'total' => 0,
            'page' => $request->input('page', 1),
            'size' => $request->input('size', 12),
            'query' => $query ? $query : "null",
            'products' => []
        ]);
    }

    public function filter(FilterRequest $request)
    {
        $filters = $this->elasticSearchTypeService->filterType($request);
        $data = $this->elasticSearchProductService->filterProducts($filters, $request->input('page', 1), $request->input('size', 12));
        
        return ResponseHelper::success('Ürünler Bulundu', [
            'total' => $data['results']['total'],
            'page' => $request->input('page', 1),
            'size' => $request->input('size', 12),
            'filters' => $filters,
            'products' => $data['products'],
        ]);
    }

    public function sorting(Request $request)
    {
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        $data = $this->elasticSearchProductService->sortingProducts($sorting, $request->input('page', 1), $request->input('size', 12));

        return ResponseHelper::success('Sıralama', [
            'total' => $data['results']['total'],
            'page' => $request->input('page', 1),
            'size' => $request->input('size', 12),
            'sorting' => $sorting,
            'products' => $data['products'],
        ]);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $data = $this->elasticSearchProductService->autocomplete($query);
        return ResponseHelper::success('Otomatik Tamamlandı', $data);
    }
    
}