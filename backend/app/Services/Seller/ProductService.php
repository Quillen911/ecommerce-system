<?php

namespace App\Services\Seller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use App\Repositories\Contracts\AuthenticationRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;

class ProductService
{
    protected $productRepository;
    protected $categoryRepository;
    protected $storeRepository;
    protected $authenticationRepository;
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    public function __construct(

        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        StoreRepositoryInterface $storeRepository,
        AuthenticationRepositoryInterface $authenticationRepository,
        ElasticSearchTypeService $elasticSearchTypeService,
        ElasticSearchProductService $elasticSearchProductService
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
        $this->authenticationRepository = $authenticationRepository;
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
    }

    public function indexProduct()
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);

        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        return $this->productRepository->getProductsByStore($store->id);
    }

    public function createProduct(array $request)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        return DB::transaction(function () use ($request, $store) {

            $productData = $request;
            $productData['store_id']      = $store->id;
            $productData['slug']           = Str::slug($productData['title'], '-' . Str::random(5));
            $productData['category_id']    = $productData['category_id'];
            $productData['is_published']  = true;
            $productData['meta_title']    = $this->generateMetaTitle($productData, $store);
            $productData['meta_description'] = $productData['meta_description'] ?? $this->generateMetaDescription($productData, $store);

            $product = $this->productRepository->createProduct($productData);

            $product->update([
                'slug' => Str::slug($product->title . '-' . $product->id)
            ]);

            foreach ($request['variants'] as $index => $variantData) {
                $sku = $this->generateSku($product, $variantData, $index);

                $variant = $product->variants()->create([
                    'color_name'     => $variantData['color_name'],
                    'color_code'     => $variantData['color_code'] ?? null,
                    'sku'            => $sku,
                    'slug'           => $this->generateVariantSlug($product, $variantData['color_name'], $index),
                    'price_cents'    => $variantData['price_cents'],
                    'is_popular'     => $variantData['is_popular'] ?? false,
                ]);

                $variant->update([
                    'sku'  => $this->generateSkuForVariant($product, $variant),
                    'slug' => $this->generateSlugForVariant($product, $variant),
                ]);

                if ($request["variants"][$index]['images']) {
                    foreach ($request["variants"][$index]['images'] as $file) {
                        $variant->variantImages()->create([
                            'image' => $file,
                        ]);
                    }
                }

                if (!empty($variantData['sizes'])) {
                    foreach ($variantData['sizes'] as $size) {
                        $variantSize = $variant->variantSizes()->create([
                            'size_option_id'    => $size['size_option_id'],
                            'sku'                => $this->generateSizeSku($sku, $size['size_option_id']),
                            'price_cents'       => $size['price_cents'] ?? $variantData['price_cents'],
                        ]);
                    
                

                        if (!empty($size['inventory'])) {
                            foreach ($size['inventory'] as $inv) {
                                $variantSize->inventory()->create([
                                    'on_hand' => $inv['on_hand'],
                                    'reserved'    => $inv['reserved'] ?? 0,
                                    'warehouse_id' => $inv['warehouse_id'] ?? $variantData['warehouse_id'] ?? 1,
                                ]);
                            }
                        }
                    }
                }
            }

            return $product;
        });
    }

    public function updateProduct(array $request, $id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if (! $seller) {
            throw new \Exception('Satıcı bulunamadı');
        }

        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if (! $store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        return DB::transaction(function () use ($request, $store, $id) {
            $product = $this->productRepository->getProductByStore($store->id, $id);
            if (! $product) {
                throw new \Exception('Ürün bulunamadı');
            }

            $oldTitle = $product->title;

            $productData = $request;
            $productData['store_id'] = $store->id;
            $this->productRepository->updateProduct($productData, $store->id, $id);
            $product->refresh();

            if (! empty($request['variants'])) {
                foreach ($request['variants'] as $index => $variantData) {
                    

                    $variant = $product->variants()->where('id', $variantData['id'])->first();
                    if ($variant) {
                        $variant->update([
                            'color_name'  => $variantData['color_name'],
                            'color_code'  => $variantData['color_code'] ?? null,
                            'price_cents' => $variantData['price_cents'] ?? $variant->price_cents,
                            'is_popular'  => $variantData['is_popular'] ?? $variant->is_popular,
                        ]);
                        $variant->update([
                            'sku'  => $this->generateSkuForVariant($product, $variant),
                            'slug' => $this->generateSlugForVariant($product, $variant),
                        ]);
                    } else {
                        throw new \Exception('Güncellenecek varyant bulunamadı. ID eksik veya hatalı.');
                    }

                    if (! empty($variantData['sizes'])) {
                        foreach ($variantData['sizes'] as $sizeData) {
                            $variantSize = $variant->variantSizes()
                                ->updateOrCreate(
                                    ['size_option_id' => $sizeData['size_option_id']],
                                    [
                                        'price_cents' => $sizeData['price_cents'] ?? $variant->price_cents,
                                        'sku'         => $this->generateSizeSku($variant->sku, $sizeData['size_option_id']),
                                    ]
                                );

                            if (! empty($sizeData['inventory'])) {
                                foreach ($sizeData['inventory'] as $inv) {
                                    $variantSize->inventory()
                                        ->updateOrCreate(
                                            ['warehouse_id' => $inv['warehouse_id'] ?? 1],
                                            [
                                                'on_hand'   => $inv['on_hand'],
                                                'reserved'  => $inv['reserved'] ?? 0,
                                            ]
                                        );
                                }
                            } else {
                                $variantSize->inventory()->delete();
                            }
                        }
                    }
                }
            }

            if ($oldTitle !== $product->title) {
                $product->update([
                    'slug' => Str::slug($product->title . '-' . $product->id),
                ]);

                foreach ($product->variants as $variant) {
                    $variant->update([
                        'sku'  => $this->generateSkuForVariant($product, $variant),
                        'slug' => $this->generateSlugForVariant($product, $variant),
                    ]);
                    foreach ($variant->variantSizes as $variantSize) {
                        $variantSize->update([
                            'sku' => $this->generateSizeSku($variant->sku, $variantSize->size_option_id),
                        ]);
                    }
                }
            }

            return $product->fresh();
        });
    }

    public function showProduct($id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
    
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }
        $product = $this->productRepository->getProductByStore($store->id, $id);
        if (!$product) {
            throw new \Exception('Ürün bulunamadı');
        }
        if ($product->store_id !== $seller->store->id) {
            throw new \Exception('Bu ürüne erişim yetkiniz yok.');
        }
        return $product;
    }

    public function deleteProduct($id)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        $product = $this->productRepository->getProductByStore($store->id, $id);
        if (!$product) {
            throw new \Exception('Ürün bulunamadı');
        }

        foreach ($product->variants as $variant) {
            foreach ($variant->variantImages as $image) {
                Storage::disk('public')->delete('productImages/' . $image->image);
            }
            $variant->variantImages()->delete();
            $variant->variantAttributes()->delete();
            $variant->delete();
        }

        return $product->delete();
    }
    public function searchProduct($request)
    {
        $seller = $this->authenticationRepository->getSeller();
        if(!$seller){
            throw new \Exception('Satıcı bulunamadı');
        }
        $store = $this->storeRepository->getStoreBySellerId($seller->id);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }
        $query = $request->input('q', '') ?? '';
        $filters = $this->elasticSearchTypeService->filterType($request);
        $filters['store_id'] = $store->id;
        $sorting = $this->elasticSearchTypeService->sortingType($request);
        $data = $this->elasticSearchProductService->searchProducts($query, $filters, $sorting, $request->input('page', 1), $request->input('size', 12));
        return $data;
    }

    private function generateMetaTitle($request, $store)
    {
        $title = $request['title'] ?? '';
        $author = $request['author'] ?? '';
        $storeName = $store->name ?? '';

        $metaTitle = $title;

        if ($author) {
            $metaTitle .= " - {$author}";
        }

        if ($storeName) {
            $metaTitle .= " | {$storeName}";
        }

        return strlen($metaTitle) > 60 ? substr($metaTitle, 0, 57) . '...' : $metaTitle;
    }

    private function generateMetaDescription($request, $store)
    {
        $title = $request['title'] ?? '';
        $author = $request['author'] ?? '';
        $description = $request['description'] ?? '';
        $price = $request['list_price'] ?? 0;

        $metaDescription = $title;

        if ($description) {
            $shortDesc = strlen($description) > 100 ? substr($description, 0, 97) . '...' : $description;
            $metaDescription .= " {$shortDesc}";
        }

        if ($price > 0) {
            $metaDescription .= " En uygun fiyat: " . number_format($price, 2) . " TL.";
        }

        $metaDescription .= " Hızlı kargo, güvenli ödeme.";

        return strlen($metaDescription) > 160 ? substr($metaDescription, 0, 157) . '...' : $metaDescription;
    }

    private function generateSku($product, array $variantData, int $index): string
    {
        $prefix = strtoupper(Str::slug(substr($product->title, 0, 3)));
        $color  = Str::upper(Str::slug($variantData['color_name']));
        return "{$prefix}-{$product->id}-{$color}-" . ($index + 1);
    }

    private function generateSkuForVariant($product, $variant): string
    {
        $prefix = strtoupper(Str::slug(substr($product->title, 0, 3)));
        $color  = Str::upper(Str::slug($variant->color_name, 0, 3));
        return "{$prefix}-{$product->id}-{$color}-{$variant->id}";
    }

    private function generateVariantSlug($product, string $colorName, int $index): string
    {
        $productSlug = Str::slug($product->title);
        $colorSlug   = Str::slug($colorName, 0, 3);
        return "{$productSlug}-{$colorSlug}-" . ($index + 1);
    }

    private function generateSlugForVariant($product, $variant): string
    {
        $productSlug = Str::slug($product->title );
        $colorSlug   = Str::slug($variant->color_name, 0, 3);
        return "{$productSlug}-{$colorSlug}-{$variant->id}";
    }

    private function generateSlug($product, $variantData)
    {
        $productSlug = Str::slug($product->title);
        $colorSlug = Str::slug($variantData['color_name']);
        return "{$colorSlug}-{$productSlug}-" . $variantData['id'];
    }
    private function generateSizeSku(string $variantSku, int $sizeOptionId): string
    {
        return $variantSku . '-' . Str::upper(Str::slug($sizeOptionId));
    }

}
