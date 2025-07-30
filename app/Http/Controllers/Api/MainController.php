<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Helpers\ResponseHelper;
use App\Services\ElasticsearchService;



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
        return ResponseHelper::success('Ürünler', $products);
    }
    public function show(Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product){
            return ResponseHelper::notFound('Ürün bulunamadı.');
        }
        return ResponseHelper::success('Ürün', $product);
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
        $results = $this->elasticSearch->searchProducts($query, $filters, $page, $size);
   
        // Elasticsearch sonuçlarından Product ID'lerini al
        $productIds = collect($results['hits'])->pluck('_id')->toArray();

        // Bu ID'lerle Product modellerini veritabanından çek
        $products = Product::with('category')->whereIn('id', $productIds)->get();

        return ResponseHelper::success('Ürünler', $products);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $results = $this->elasticSearch->autocomplete($query);
        Cache::flush();
        return ResponseHelper::success('Otomatik Tamamlama', $results);
    }
    
}