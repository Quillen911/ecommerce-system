<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\Search\ElasticsearchService;
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

    public function main()
    {
        $page = request('page', 1);
        $products = Cache::remember("products.page.$page", 60, function () {
            return Product::with('category')->orderBy('id')->paginate(100);
        });
        
        $categories = Category::all();
        
        return view('main', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '') ?? '';
        $filters = $this->elasticSearchTypeService->filterType($request);
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        $results = $this->elasticSearch->searchProducts($query, $filters, $sorting, $page, $size);
        $products = collect($results['hits'])->pluck('_source')->toArray();
        $categories = Category::all();
                
        return view('main', compact('query', 'results', 'products', 'categories', 'sorting', 'filters'));
    }

    public function filter(Request $request)
    {
        $filters = $this->elasticSearchTypeService->filterType($request);
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        $results = $this->elasticSearch->filterProducts($filters, $page, $size);
        $products = collect($results['hits'])->pluck('_source')->toArray();
        $categories = Category::all();

        return view('main', compact('results', 'products', 'categories'));
    }

    public function sorting(Request $request)
    {
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);
        $results = $this->elasticSearch->sortProducts($sorting, $page, $size);
        
        $products = collect($results['hits'])->pluck('_source')->toArray();
        $categories = Category::all();

        return view('main', compact('results', 'products', 'categories', 'sorting'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '') ?? '';
        $results = $this->elasticSearch->autocomplete($query);

        $products = collect($results)->pluck('_source')->toArray();
        $categories = Category::all();
            
        return view('main', compact('query', 'results', 'products', 'categories'));
    }
}