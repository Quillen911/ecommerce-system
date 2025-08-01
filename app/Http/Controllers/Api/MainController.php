<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Helpers\ResponseHelper;
use App\Services\ElasticsearchService;
use App\Http\Requests\FilterRequest;


class MainController extends Controller
{
    protected $elasticSearch;
    
    public function __construct(ElasticsearchService $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    public function index(Request $request)
    {
        $page = request('page', 1);
        $products = Cache::remember("products.page.$page", 60, function () {
            return Product::with('category')->orderBy('id')->paginate(20);
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
        $filters = [];
        if($request->filled('category_title')){
            $filters['category_title'] = $request->input('category_title');
        }
        if($request->filled('min_price')){
            $filters['min_price'] = $request->input('min_price');
        }
        if($request->filled('max_price')){
            $filters['max_price'] = $request->input('max_price');
        }
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        $results = $this->elasticSearch->searchProducts($query, $filters, $page, $size);
        
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
        return ResponseHelper::success('Ürün bulunamadı.', [
            'total' => 0,
            'page' => $page,
            'size' => $size,
            'query' => $query ? $query : "null",
            'products' => []
        ]);
    }

    public function filter(FilterRequest $request)
    {
        $filters = [];
        if($request->filled('category_title')){
            $filters['category_title'] = $request->input('category_title') ?? '';
        }
        if($request->filled('min_price')){
            $filters['min_price'] = $request->input('min_price') ?? '';
        }
        if($request->filled('max_price')){
            $filters['max_price'] = $request->input('max_price') ?? '';
        }
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

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $results = $this->elasticSearch->autocomplete($query);
        Cache::flush();
        return ResponseHelper::success('Otomatik Tamamlama', $results);
    }
    
}