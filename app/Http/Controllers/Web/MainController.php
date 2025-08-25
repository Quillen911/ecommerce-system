<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\MainService;
use App\Services\Search\ElasticSearchProductService;

class MainController extends Controller
{
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $mainService;
    protected $elasticSearchProductService;
    
    public function __construct(
        ElasticsearchService $elasticSearch, 
        ElasticSearchTypeService $elasticSearchTypeService, 
        MainService $mainService, 
        ElasticSearchProductService $elasticSearchProductService, 
    ) {
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->mainService = $mainService;
        $this->elasticSearchProductService = $elasticSearchProductService;
    }

    public function main()
    {
        $products = $this->mainService->getProducts();
        $categories = $this->mainService->getCategories();

        return view('main', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '') ?? '';
        $filters = $this->elasticSearchTypeService->filterType($request);
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        
        $data = $this->elasticSearchProductService->searchProducts($query, $filters, $sorting, $request->input('page', 1), $request->input('size', 12));

        return view('main', array_merge($data, [
            'query' => $query,
            'categories' => $this->mainService->getCategories(),
            'sorting' => $sorting,
            'filters' => $filters
        ]));
    }

    public function filter(Request $request)
    {
        $filters = $this->elasticSearchTypeService->filterType($request);
        $data = $this->elasticSearchProductService->filterProducts($filters, $request->input('page', 1), $request->input('size', 12));
        return view('main', array_merge($data, [
            'filters' => $filters,
            'categories' => $this->mainService->getCategories()
        ]));
    }

    public function sorting(Request $request)
    {
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        $data = $this->elasticSearchProductService->sortingProducts($sorting, $request->input('page', 1), $request->input('size', 12));
        return view('main', array_merge($data, [
            'categories' => $this->mainService->getCategories(),
            'sorting' => $sorting
        ]));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '') ?? '';
        $data = $this->elasticSearchProductService->autocomplete($query);
        return view('main', array_merge($data, [
            'query' => $query,
            'categories' => $this->mainService->getCategories()
        ]));
    }
}