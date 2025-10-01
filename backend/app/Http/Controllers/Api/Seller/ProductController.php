<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Http\Requests\Seller\Product\BulkProductStoreApiRequest;
use App\Services\Seller\ProductService;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductVariantResource;
use App\Models\Product;

class ProductController extends Controller
{
    protected $productService;
    protected $elasticSearch;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    protected $productRepository;
    protected $categoryRepository;
    protected $storeRepository;
    protected $authenticationRepository;
    public function __construct(
        ProductService $productService, 
        ElasticsearchService $elasticSearch, 
        ElasticSearchTypeService $elasticSearchTypeService, 
        ElasticSearchProductService $elasticSearchProductService,
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        StoreRepositoryInterface $storeRepository,
        AuthenticationRepositoryInterface $authenticationRepository
    ) {
        $this->productService = $productService;
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
        $this->authenticationRepository = $authenticationRepository;
    }

    public function index()
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            
            $products = $this->productService->indexProduct($seller->id);

            return ProductResource::collection($products->load([
                'category.parent',
                'category.children',
                'variants.variantAttributes.attribute',
                'variants.variantImages',
                'variants.variantAttributes.option'
            ]));
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürünler alınamadı: ' . $e->getMessage());
        }
    }
    public function store(ProductStoreRequest $request)
    {
        $seller = $this->authenticationRepository->getSeller();

        $product = $this->productService->createProduct($seller->id, $request->validated());

        return new ProductResource(
            $product->load([
                'category.parent',
                'category.children',
                'variants.variantAttributes.attribute',
                'variants.variantImages',
                'variants.variantAttributes.option'
            ])
        );
    }

    public function show(Product $product)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            if ($product->store_id !== $seller->store->id) {
                return ResponseHelper::error('Bu ürüne erişim yetkiniz yok.');
            }
            return new ProductResource(
                $product->load([
                    'category.parent',
                    'category.children',
                    'variants.variantAttributes.attribute',
                    'variants.variantImages',
                    'variants.variantAttributes.option'
                ])
            );
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün bulunamadı: ' . $e->getMessage());
        }
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();

            $data = $request->validated();

            $variants = $request->input('variants', []);

            foreach ($variants as $i => $v) {
                if ($request->hasFile("variants.$i.images")) {
                    $data['variants'][$i]['images'] = $request->file("variants.$i.images");
                }
            }
            
            $product = $this->productService->updateProduct($seller->id, $data, $id);
            
            return new ProductResource(
                $product->load([
                    'category.parent',
                    'category.children',
                    'variants.variantAttributes.attribute',
                    'variants.variantImages',
                    'variants.variantAttributes.option'
                ])
            );
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün güncellenemedi: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
            $product = $this->productService->deleteProduct($seller->id, $id);
            
            return ResponseHelper::success('Ürün başarıyla silindi.');
        }
        catch(\Exception $e){
            return ResponseHelper::error('Ürün silinemedi: ' . $e->getMessage());
        }
    }
    public function bulkStore(BulkProductStoreApiRequest $request)
    {
        try {
            $seller = $this->authenticationRepository->getSeller();
            $productsData = $request->validated()['products'];
            
            $products = $this->productService->bulkStoreProductApi($productsData, $seller->id);
            
            return ProductResource::collection(
                collect($products)->load([
                    'category.parent',
                    'category.children',
                    'variants.variantAttributes.attribute',
                    'variants.variantImages',
                    'variants.variantAttributes.option'
                ])
            );
        } catch(\Exception $e){
            return ResponseHelper::error('Ürünler oluşturulamadı: ' . $e->getMessage());
        }
    }

    public function searchProduct(Request $request)
    {
        try{
            $seller = $this->authenticationRepository->getSeller();
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
                    'products' => ProductResource::collection(collect($data['products'])->load([
                        'category.parent',
                        'category.children',
                        'variants.variantAttributes.attribute',
                        'variants.variantImages',
                        'variants.variantAttributes.option'
                    ])),
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
