<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ElasticsearchService;

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
            return Product::with('category')->orderBy('id')->paginate(20);
        });
        Cache::flush();
        return view('main', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        $filters = [
            'category_id' => $request->input('category_id'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
        ];
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        if (empty($query)) {
            return $this->main();
        }

        $results = $this->elasticSearch->searchProducts($query, $filters, $page, $size);
        
        $productIds = collect($results['hits'])->pluck('_id')->toArray();

        $products = Product::with('category')->whereIn('id', $productIds)->get();

        return view('main', compact('query', 'results', 'products'));
    }
    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $results = $this->elasticSearch->autocomplete($query);

        $productIds = collect($results['hits'])->pluck('_id')->toArray();

        $products = Product::with('category')->whereIn('id', $productIds)->get();

        return view('main', compact('query', 'results', 'products'));
    }
}