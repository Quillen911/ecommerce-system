<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Http\Requests\Seller\Product\BulkProductStoreApiRequest;
use App\Services\Seller\ProductService;
use App\Http\Resources\Product\ProductResource;


class ProductController extends Controller
{
    protected $productService;
    public function __construct(
        ProductService $productService, 
    ) {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->indexProduct();
        return ProductResource::collection($products->load($this->getProductLoadRelations()));
    }
    
    public function store(ProductStoreRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());

        return new ProductResource(
            $product->load($this->getProductLoadRelations())
        );
    }

    public function productDetail($id)
    {
        $product = $this->productService->productDetail($id);
        return new ProductResource(
            $product->load($this->getProductLoadRelations()));
    }
    
    public function update(ProductUpdateRequest $request, $id)
    {
        $data = $request->validated();        
        $product = $this->productService->updateProduct($data , $id);

        return new ProductResource($product->load($this->getProductLoadRelations()));
    }
    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return ResponseHelper::success('Ürün başarıyla silindi.');
    }

    public function searchProduct(Request $request)
    {
        $data = $this->productService->searchProduct($request);
            
            if(!empty($data['products'])){
                return ResponseHelper::success('Ürünler Bulundu', [
                    'total' => $data['results']['total'],
                    'page' => $request->input('page', 1),
                    'size' => $request->input('size', 12),
                    'query' => $request->input('q', '') ?? '',
                    'products' => ProductResource::collection(collect($data['products'])->load($this->getProductLoadRelations())),
                ]);
            }

            return ResponseHelper::notFound('Ürün bulunamadı.', [
                'total' => 0,
                'page' => $request->input('page', 1),
                'size' => $request->input('size', 12),
                'query' => $request->input('q', '') ?? '',
                'products' => []
                ]);
    }

    protected function getProductLoadRelations()
    {
        return [
            'category.parent',
            'category.gender',
            'variants.variantAttributes.attribute',
            'variants.variantImages',
            'variants.variantAttributes.option',
            'variants.variantSizes.inventory',
            'variants.variantSizes.sizeOption'
        ];
    }
}
