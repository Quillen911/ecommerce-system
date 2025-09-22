<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\MainService;
use App\Services\Search\ElasticSearchProductService;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

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
        ElasticSearchProductService $elasticSearchProductService
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

    public function productDetail(Product $product)
    {
        abort_unless($product->is_published, 404);
        $product = Cache::remember("product:{$product->id}:detail",
        now()->addMinutes(15),
        fn() => $product->load(
            'category:id,category_title,category_slug',
            'store:id,name',
            'variants.variantAttributes.attribute:id,name,code,input_type',
            'variants.variantAttributes.option:id,attribute_id,value,slug'
        ));

        $similar = Product::published()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(20)
            ->get(['id', 'slug', 'title', 'list_price', 'images']);

        $similarSeller = Product::published()
            ->where('store_id', $product->store_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(20)
            ->get(['id', 'slug', 'title', 'list_price', 'images']);

        return view('product-detail', compact('product', 'similar', 'similarSeller'));
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

    public function categoryFilter(Request $request, $category_slug)
    {
        $category = $this->mainService->getCategory($category_slug);
        if(!$category){
            return redirect()->route('main')->with('error', 'Kategori bulunamadı');
        }
        
        $request->merge(['category_title' => $category->category_title]);
        $filters = $this->elasticSearchTypeService->filterType($request);
        $data = $this->elasticSearchProductService->filterProducts($filters, $request->input('page', 1), $request->input('size', 12));
        
        return view('main', array_merge($data, [
            'filters' => $filters,
            'categories' => $this->mainService->getCategories(),
            'category' => $category
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
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
        return view('main', array_merge($data, [
            'query' => $query,
            'categories' => $this->mainService->getCategories()
        ]));
    }
}
