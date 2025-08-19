<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Services\Seller\ProductService;
use App\Models\Category;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Models\Store;
use App\Services\Search\ElasticSearchProductService;

class ProductController extends Controller
{
    protected $productService;
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;

    public function __construct(ProductService $productService, ElasticsearchService $elasticSearch, ElasticSearchTypeService $elasticSearchTypeService, ElasticSearchProductService $elasticSearchProductService)
    {
        $this->productService = $productService;
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
    }

    public function product()
    {
        $seller = auth('seller_web')->user();
        if(!$seller){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return redirect()->route('seller.product')->with('error', 'Mağaza bulunamadı');
        }
        $products = $this->productService->indexProduct($store->id);
        $categories = $this->productService->getCategories();
        return view('Seller.Product.product', compact('products', 'categories'));
    }

    public function storeProduct()
    {
        return view('Seller.Product.storeProduct'); 
    }

    public function createProduct(ProductStoreRequest $request) 
    {
        try {
            $products = $this->productService->createProduct($request);
            return redirect()->route('seller.product')->with('success', 'Ürün başarıyla eklendi');
        } catch (\Exception $e) {
            return redirect()->route('seller.product')->with('error', $e->getMessage());
        }
    }

    public function editProduct($id)
    {
        $products = $this->productService->showProduct($id);
        return view('Seller.Product.editProduct', compact('products'));
    }

    public function updateProduct(ProductUpdateRequest $request, $id)
    {
        $products = $this->productService->updateProduct($request, $id);
        return redirect()->route('seller.product')->with('success', 'Ürün başarıyla güncellendi');
    }

    public function deleteProduct($id)
    {
        $products = $this->productService->deleteProduct($id);
        return redirect()->route('seller.product')->with('success', 'Ürün başarıyla silindi');
    }

    public function searchProduct(Request $request)
    {
        $seller = auth('seller_web')->user();
        if(!$seller){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $store = Store::where('seller_id', $seller->id)->first();
        if(!$store){
            return redirect()->route('seller.product')->with('error', 'Mağaza bulunamadı');
        }
        $query = $request->input('q', '') ?? '';
        $filters = $this->elasticSearchTypeService->filterType($request);
        $filters['store_id'] = $store->id;
        $sorting = $this->elasticSearchTypeService->sortingType($request);

        $data = $this->elasticSearchProductService->searchProducts($query, $filters, $sorting, $request->input('page', 1), $request->input('size', 12));
        return view('Seller.Product.product', array_merge($data, [
            'query' => $query,
            'categories' => $this->productService->getCategories(),
            'filters' => $filters,
            'sorting' => $sorting,
        ]));
    }
}
