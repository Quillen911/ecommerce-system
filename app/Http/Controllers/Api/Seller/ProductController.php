<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Services\Seller\ProductService;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;

class ProductController extends Controller
{
    protected $productService;
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    protected $productRepository;
    protected $categoryRepository;
    protected $storeRepository;

    public function __construct(
        ProductService $productService, 
        ElasticsearchService $elasticSearch, 
        ElasticSearchTypeService $elasticSearchTypeService, 
        ElasticSearchProductService $elasticSearchProductService,
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->productService = $productService;
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        try{
            $seller = auth('seller')->user();
            
            $products = $this->productService->indexProduct($seller->id);
            if($products->isEmpty()){
                return ResponseHelper::notFound('Ürün bulunamadı');
            }

            return ResponseHelper::success('Ürünler başarıyla listelendi', $products);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürünler alınamadı: ' . $e->getMessage());
        }
    }
    public function store(ProductStoreRequest $request)
    {
        try{
            $seller = auth('seller')->user();
            $products = $this->productService->createProduct($seller->id, $request->validated());
            if(!$products){
                return ResponseHelper::error('Ürün oluşturulamadı');
            }
            return ResponseHelper::success('Ürün başarıyla oluşturuldu', $products);
            }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün oluşturulamadı: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        try{
            $seller = auth('seller')->user();
            $products = $this->productService->showProduct($seller->id, $id);
            if(!$products){
                return ResponseHelper::notFound('Ürün bulunamadı');
            }
            return ResponseHelper::success('Ürün başarıyla listelendi', $products);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün bulunamadı: ' . $e->getMessage());
        }
    }
    public function update(ProductUpdateRequest $request, $id)
    {
        try{
            $seller = auth('seller')->user();
            $products = $this->productService->updateProduct($seller->id, $request->validated(), $id);
            if(!$products){
                return ResponseHelper::notFound('Ürün bulunamadı');
            }
            return ResponseHelper::success('Product updated successfully', $products);
            }

        catch(\Exception $e){
            return ResponseHelper::error('Ürün güncellenemedi: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try{
            $seller = auth('seller')->user();
            $products = $this->productService->deleteProduct($seller->id, $id);
            if(!$products){
                return ResponseHelper::notFound('Ürün bulunamadı');
            }
            return ResponseHelper::success('Ürün başarıyla silindi', $products);
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün silinemedi: ' . $e->getMessage());
        }
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
        try{
            $seller = auth('seller')->user();
            if(!$seller){
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $store = $this->storeRepository->getStoreBySellerId($seller->id);
            if(!$store){
                return ResponseHelper::error('Mağaza bulunamadı');
            }
            $query = $request->input('q', '') ?? '';
            $filters = $this->elasticSearchTypeService->filterType($request);
            $filters['store_id'] = $store->id;
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
        catch(\Exception $e){
            return ResponseHelper::error('Ürün arama hatası: ' . $e->getMessage());
        }
    }
}
