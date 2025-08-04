<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\Search\ElasticsearchService;

class MainController extends Controller
{
    protected $elasticSearch;
        
    public function __construct(ElasticsearchService $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
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
        $categories = Category::all();
                
        return view('main', compact('query', 'results', 'products', 'categories'));
    }

    public function filter(Request $request)
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
        $categories = Category::all();

        return view('main', compact('results', 'products', 'categories'));
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