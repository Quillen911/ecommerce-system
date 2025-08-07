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

class MainController extends Controller
{
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    public function __construct(ElasticsearchService $elasticSearch, ElasticSearchTypeService $elasticSearchTypeService)
    {
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
    }

    public function index(Request $request)
    {
        $page = request('page', 1);
        $products = Cache::remember("products.page.$page", 60, function () {
            return Product::with('category')->orderBy('id')->paginate(100);
        });
        $categories = Category::all();
        return ResponseHelper::success('Ürünler', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function show(Request $request, $id)
    {
        $product = Product::find($id);
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
        
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        $results = $this->elasticSearch->searchProducts($query, $filters, $sorting, $page, $size);
        
        $products = collect($results['hits'])->pluck('_source')->toArray();

        if(!empty($products)){
            return ResponseHelper::success('Ürünler Bulundu', [
            'total' => $results['total'],
            'page' => $page,
            'size' => $size,
            'query' => $query ? $query : "null",
            'products' => $products,
        ]);
        }
        return ResponseHelper::notFound('Ürün bulunamadı.', [
            'total' => 0,
            'page' => $page,
            'size' => $size,
            'query' => $query ? $query : "null",
            'products' => []
        ]);
    }

    public function filter(FilterRequest $request)
    {
        $filters = $this->elasticSearchTypeService->filterType($request);
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        $results = $this->elasticSearch->filterProducts($filters, $page, $size);
        
        $products = collect($results['hits'])->pluck('_source')->toArray();
        return ResponseHelper::success('Ürünler Bulundu', [
            'total' => $results['total'],
            'page' => $page,
            'size' => $size,
            'filters' => $filters,
            'products' => $products,
        ]);
    }

    public function sorting(Request $request)
    {
        try{
        $sorting = $this->elasticSearchTypeService->sortingType($request);

        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        $results = $this->elasticSearch->sortProducts($sorting, $page, $size);

        $products = collect($results['hits'])->pluck('_source')->toArray();

        return ResponseHelper::success('Sıralama', [
            'total' => $results['total'],
            'page' => $page,
            'size' => $size,
            'sorting' => $sorting,
            'products' => $products,
        ]);
        }catch(\Exception $e){
            Log::error('Sıralama hatası', ['error' => $e->getMessage()]);
            return ResponseHelper::error('Sıralama hatası', $e->getMessage());
        }
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $results = $this->elasticSearch->autocomplete($query);
        Cache::flush();
        return ResponseHelper::success('Otomatik Tamamlama', $results);
    }
    
}