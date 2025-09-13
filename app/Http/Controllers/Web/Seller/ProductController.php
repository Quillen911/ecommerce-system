<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Http\Requests\Seller\Product\BulkProductStoreRequest;
use App\Services\Seller\ProductService;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
    
class ProductController extends Controller
{
    protected $productService;
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    protected $storeRepository;
    protected $categoryRepository;
    protected $productRepository;
    protected $authenticationRepository;
    public function __construct(
        ProductService $productService, 
        ElasticsearchService $elasticSearch, 
        ElasticSearchTypeService $elasticSearchTypeService, 
        ElasticSearchProductService $elasticSearchProductService,
        StoreRepositoryInterface $storeRepository,
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
        AuthenticationRepositoryInterface $authenticationRepository
    )
    {
        $this->productService = $productService;
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->storeRepository = $storeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->authenticationRepository = $authenticationRepository;
    }

    public function product()
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
        }
        $products = $this->productService->indexProduct($seller->id);
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
            $seller = $this->authenticationRepository->getSeller();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            
            $this->productService->createProduct($seller->id, $request->validated());
            return redirect()->route('seller.product')->with('success', 'Ürün başarıyla eklendi');

        } catch (\Exception $e) {
            return redirect()->route('seller.product')->with('error', 'Ürün oluşturulamadı: ' . $e->getMessage());
        }
    }

    public function bulkStoreProduct()
    {
        return view('Seller.Product.bulkStoreProduct');
    }

    public function bulkCreateProduct(BulkProductStoreRequest $request)
    {
        try {
            $seller = $this->authenticationRepository->getSeller();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            
            $products = $this->productService->bulkStoreProduct($request, $seller->id);
            
            return redirect()->route('seller.product')->with('success', count($products) . ' ürün başarıyla eklendi');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ürünler oluşturulamadı: ' . $e->getMessage());
        }
    }

    public function editProduct($id)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            $products = $this->productService->showProduct($seller->id, $id);
            return view('Seller.Product.editProduct', compact('products'));
        }
        catch(\Exception $e){
            return redirect()->route('seller.product')->with('error', 'Ürün bulunamadı: ' . $e->getMessage());
        }
    }

    public function updateProduct(ProductUpdateRequest $request, $id)
    {
        try{
            \Log::info('UpdateProduct - Request received:', $request->all());
            \Log::info('UpdateProduct - Validated data:', $request->validated());
            
            $seller = $this->authenticationRepository->getSeller();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            $this->productService->updateProduct($seller->id, $request->validated(), $id);
            return redirect()->route('seller.product')->with('success', 'Ürün başarıyla güncellendi');
        }
        catch(\Exception $e){
            \Log::error('UpdateProduct - Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('seller.product')->with('error', 'Ürün güncellenemedi: ' . $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            $this->productService->deleteProduct($seller->id, $id);
            return redirect()->route('seller.product')->with('success', 'Ürün başarıyla silindi');
        }
        catch(\Exception $e){
            return redirect()->route('seller.product')->with('error', 'Ürün silinemedi: ' . $e->getMessage());
        }
    }

    public function searchProduct(Request $request)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if(!$seller){
                return redirect()->route('seller.login')->with('error', 'Lütfen giriş yapınız');
            }
            $store = $this->storeRepository->getStoreBySellerId($seller->id);
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
        catch(\Exception $e){
            return redirect()->route('seller.product')->with('error', 'Ürün arama hatası: ' . $e->getMessage());
        }
    }
}
