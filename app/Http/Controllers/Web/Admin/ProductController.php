<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;
use App\Services\Campaigns\Admin\ProductService;
use App\Models\Category;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
class ProductController extends Controller
{
    protected $productService;
    protected $elasticSearch;
    protected $elasticSearchTypeService;

    public function __construct(ProductService $productService, ElasticsearchService $elasticSearch, ElasticSearchTypeService $elasticSearchTypeService)
    {
        $this->productService = $productService;
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
    }

    public function product()
    {
        $products = $this->productService->indexProduct();
        return view('Admin.Product.product', compact('products'));
    }

    public function storeProduct()
    {
        return view('Admin.Product.storeProduct'); 
    }

    public function createProduct(ProductStoreRequest $request) 
    {
        $products = $this->productService->createProduct($request);
        return redirect()->route('admin.product')->with('success', 'Ürün başarıyla eklendi');
    }

    public function editProduct($id)
    {
        $products = $this->productService->showProduct($id);
        return view('Admin.Product.editProduct', compact('products'));
    }

    public function updateProduct(ProductUpdateRequest $request, $id)
    {
        $products = $this->productService->updateProduct($request, $id);
        return redirect()->route('admin.product')->with('success', 'Ürün başarıyla güncellendi');
    }

    public function deleteProduct($id)
    {
        $products = $this->productService->deleteProduct($id);
        return redirect()->route('admin.product')->with('success', 'Ürün başarıyla silindi');
    }

    public function searchProduct(Request $request)
    {
        $query = $request->input('q', '') ?? '';
        $filters = $this->elasticSearchTypeService->filterType($request);
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        $page = $request->input('page', 1);
        $size = $request->input('size', 12);

        $results = $this->elasticSearch->searchProducts($query, $filters, $sorting, $page, $size);
        $products = collect($results['hits'])->pluck('_source')->toArray();
        $categories = Category::all();
        return view('Admin.Product.product', compact('query', 'results', 'products', 'filters', 'sorting', 'categories'));
    }
}