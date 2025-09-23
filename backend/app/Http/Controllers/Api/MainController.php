<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Services\MainService;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Product;
use App\Http\Resources\Product\AttributeResource;
use App\Http\Resources\Product\AttributeOptionResource;
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
        MainService $mainService
    ) {
        $this->elasticSearch = $elasticSearch;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->mainService = $mainService;
    }

    public function main(Request $request)
    {
        $products   = $this->mainService->getProducts();
        $categories = $this->mainService->getCategories();
        $campaigns  = $this->mainService->getCampaigns();
        $attributes = $this->mainService->getAttributes();
        $attributeOptions = $this->mainService->getAttributeOptions();

        return ResponseHelper::success('Ana Sayfa', [
            'products'   => ProductResource::collection($products),
            'categories' => CategoryResource::collection($categories),
            'campaigns'  => $campaigns,
            'attributes' => AttributeResource::collection($attributes),
            'attributeOptions' => AttributeOptionResource::collection($attributeOptions),
        ]);
    }

    public function show($id)
    {
        $product = $this->mainService->getProduct($id);
        if (!$product) {
            return ResponseHelper::notFound('Ürün bulunamadı.');
        }
        return ResponseHelper::success('Ürün', new ProductResource($product));
    }

    public function productDetail($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            $category = $this->mainService->getCategory($slug);
            if ($category) {
                return $this->categoryFilter(request(), $slug);
            }
            return ResponseHelper::notFound('Sayfa bulunamadı');
        }

        abort_unless($product->is_published, 404);

        $product = Cache::remember("product:{$product->id}:detail",
            now()->addMinutes(15),
            fn() => $product->load(
                'category:id,category_title,category_slug,parent_id',
                'store:id,name',
                'variants.variantAttributes.attribute',
                'variants.variantAttributes.option'
            )
        );

        $similar = Product::published()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(20)
            ->get();

        return ResponseHelper::success('Ürün Detayı', [
            'product' => new ProductResource($product),
            'similar' => ProductResource::collection($similar)
        ]);
    }

    public function search(Request $request)
    {
        $query   = $request->input('q', '');
        $filters = $this->elasticSearchTypeService->filterType($request);
        $sorting = $this->elasticSearchTypeService->sortingType($request);

        $data = $this->elasticSearchProductService->searchProducts(
            $query,
            $filters,
            $sorting,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        if (!empty($data['products'])) {
            return ResponseHelper::success('Ürünler Bulundu', [
                'total'    => $data['results']['total'],
                'page'     => $request->input('page', 1),
                'size'     => $request->input('size', 12),
                'query'    => $query ?: "null",
                'products' => ProductResource::collection($data['products']),
            ]);
        }

        return ResponseHelper::notFound('Ürün bulunamadı.', [
            'total'    => 0,
            'page'     => $request->input('page', 1),
            'size'     => $request->input('size', 12),
            'query'    => $query ?: "null",
            'products' => []
        ]);
    }

    public function categoryFilter(Request $request, $category_slug)
    {
        $category = $this->mainService->getCategory($category_slug);

        if (!$category) {
            return ResponseHelper::notFound('Kategori bulunamadı.');
        }

        $request->merge(['category_title' => $category->category_title]);
        $filters = $this->elasticSearchTypeService->filterType($request);

        $data = $this->elasticSearchProductService->filterProducts(
            $filters,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        return ResponseHelper::success('Kategori Ürünleri', [
            'products'   => ProductResource::collection($data['products']),
            'filters'    => $filters,
            'categories' => CategoryResource::collection($this->mainService->getCategories()),
            'category'   => new CategoryResource($category),
            'pagination' => [
                'page' => $request->input('page', 1),
                'size' => $request->input('size', 12)
            ]
        ]);
    }

    public function filter(Request $request)
    {
        $filters = $this->elasticSearchTypeService->filterType($request);

        $data = $this->elasticSearchProductService->filterProducts(
            $filters,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        return ResponseHelper::success('Filtre Sonucu', [
            'total'    => $data['results']['total'],
            'page'     => $request->input('page', 1),
            'size'     => $request->input('size', 12),
            'filters'  => $filters,
            'products' => ProductResource::collection($data['products']),
        ]);
    }

    public function sorting(Request $request)
    {
        $sorting = $this->elasticSearchTypeService->sortingType($request);

        $data = $this->elasticSearchProductService->sortingProducts(
            $sorting,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        return ResponseHelper::success('Sıralama', [
            'total'    => $data['results']['total'],
            'page'     => $request->input('page', 1),
            'size'     => $request->input('size', 12),
            'sorting'  => $sorting,
            'products' => ProductResource::collection($data['products']),
        ]);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q', '');
        $data  = $this->elasticSearchProductService->autocomplete($query);

        return ResponseHelper::success('Otomatik Tamamlama', $data);
    }
}
