<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Services\Campaigns\Seller\ProductService;
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

    public function index()
    {
        $seller = auth('seller')->user();
        if(!$seller){
            return ResponseHelper::error('Seller access required', 403);
        }
        $products = $this->productService->indexProduct();
        if($products->isEmpty()){
            return ResponseHelper::notFound('Ürün bulunamadı');
        }
        return ResponseHelper::success('Products fetched successfully', $products);
    }
    public function store(ProductStoreRequest $request)
    {
        try{
        $products = $this->productService->createProduct($request);
        if(!$products){
            return ResponseHelper::error('Ürün oluşturulamadı');
        }
        return ResponseHelper::success('Product created successfully', $products);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün oluşturulamadı');
        }
    }
    public function show($id)
    {
        $products = $this->productService->showProduct($id);
        if(!$products){
            return ResponseHelper::notFound('Ürün bulunamadı');
        }
        return ResponseHelper::success('Product fetched successfully', $products);
    }
    public function update(ProductUpdateRequest $request, $id)
    {
        try{
        $products = $this->productService->updateProduct($request, $id);
        if(!$products){
            return ResponseHelper::notFound('Ürün bulunamadı');
        }
        return ResponseHelper::success('Product updated successfully', $products);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün güncellenemedi');
        }
    }
    public function destroy($id)
    {
        $products = $this->productService->deleteProduct($id);
        if(!$products){
            return ResponseHelper::notFound('Ürün bulunamadı');
        }
        return ResponseHelper::success('Ürün başarıyla silindi', $products);
    }
    public function bulkStore(Request $request)
    {
        $products = $this->productService->bulkStoreProduct($request);
        if(!$products){
            return ResponseHelper::error('Ürünler oluşturulamadı');
        }
        return ResponseHelper::success('Ürünler başarıyla oluşturuldu', $products);
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
        if(!empty($products)){
            return ResponseHelper::success('Ürünler Bulundu', [
            'total' => $results['total'],
            'page' => $page,
            'size' => $size,
            'query' => $query ? $query : "null",
            'products' => $products,
        ]);
        }
        return ResponseHelper::notFound('Ürün bulunamadı.', [
            'total' => 0,
            'page' => $page,
            'size' => $size,
            'query' => $query ? $query : "null",
            'products' => []
        ]);
    }
}
