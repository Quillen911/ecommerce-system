<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Services\Search\ElasticsearchService;
use App\Http\Requests\FilterRequest;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Services\MainService;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    protected $mainService;

    public function __construct(
        ElasticsearchService $elasticSearch, 
        ElasticSearchTypeService $elasticSearchTypeService, 
        ElasticSearchProductService $elasticSearchProductService, 
        MainService $mainService, 
    ) {
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->mainService = $mainService;
    }

    public function index(Request $request)
    {
        $products = $this->mainService->getProducts();
        $categories = $this->mainService->getCategories();
        return ResponseHelper::success('Ürünler', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function show(Request $request, $id)
    {
        $product = $this->mainService->getProduct($id);
        if(!$product){
            return ResponseHelper::notFound('Ürün bulunamadı.');
        }
        return ResponseHelper::success('Ürün', $product);
    }

    public function productDetail($slug)
    {
        // Önce ürün olarak ara
        $product = Product::where('slug', $slug)->first();

        if(!$product) {
            // Ürün bulunamadı, kategori olabilir mi kontrol et
            $category = $this->mainService->getCategory($slug);
            if($category) {
                return $this->categoryFilter(request(), $slug); // Kategori sayfasına yönlendir
            }
            return ResponseHelper::notFound('Sayfa bulunamadı');
        }

        // If product is found, proceed with product detail logic
        abort_unless($product->is_published, 404);
        $product = Cache::remember("product:{$product->id}:detail",
        now()->addMinutes(15),
        fn() => $product->load('category', 'store'));

        $similar = Product::published()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(20)
            ->get(['id', 'slug', 'title', 'list_price', 'images']);

        return ResponseHelper::success('Ürün Detayı', [
            'product' => $product,
            'similar' => $similar
        ]);
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