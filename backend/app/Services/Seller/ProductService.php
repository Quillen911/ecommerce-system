<?php

namespace App\Services\Seller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $productRepository;
    protected $categoryRepository;
    protected $storeRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
    }

    public function indexProduct($sellerId)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);

        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        return $this->productRepository->getProductsByStore($store->id);
    }

    public function createProduct($sellerId, array $request)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        return DB::transaction(function () use ($request, $store) {

            $productData = $request;
            $productData['store_id']      = $store->id;
            $productData['total_sold_quantity'] = 0;
            $productData['is_published']  = true;
            $productData['meta_title']    = $this->generateMetaTitle($productData, $store);
            $productData['meta_description'] = $productData['meta_description'] ?? $this->generateMetaDescription($productData, $store);

            $product = $this->productRepository->createProduct($productData);

            foreach ($request['variants'] as $index => $variantData) {
                $sku = $this->generateSku($product, $variantData, $index);

                $variant = $product->variants()->create([
                    'sku'            => $sku,
                    'slug'           => $sku,
                    'price'          => $variantData['price'],
                    'price_cents'    => $variantData['price'] * 100,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                ]);

                if (!empty($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attr) {
                        $variant->variantAttributes()->create([
                            'attribute_id' => $attr['attribute_id'],
                            'option_id'    => $attr['option_id'],
                        ]);
                    }
                }

                if ($request["variants"][$index]['images']) {
                    foreach ($request["variants"][$index]['images'] as $file) {
                        $variant->variantImages()->create([
                            'image' => $file,
                        ]);
                    }
                }
                $variant->update([
                    'slug' => $this->generateSlug($product, $variant->fresh()),
                ]);
            }

            return $product->load([
                    'variants.variantAttributes.attribute',
                    'variants.variantImages',
                    'variants.variantAttributes.option',
            ]);
        });
    }

    public function updateProduct($sellerId, array $request, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }
    
        return DB::transaction(function () use ($request, $store, $id) {
            $product = $this->productRepository->getProductByStore($store->id, $id);
            if (!$product) {
                throw new \Exception('Ürün bulunamadı');
            }
    
            $oldTitle = $product->title;
    
            $productData = $request;
            $productData['store_id'] = $store->id;
            $this->productRepository->updateProduct($productData, $store->id, $id);

            if (!empty($request['variants'])) {
                foreach ($request['variants'] as $variantData) {
                    $variant = $product->variants()->find($variantData['id']);
                    if ($variant) {
                        $variant->update($variantData);
        
                        if (!empty($variantData['attributes'])) {
                            foreach ($variantData['attributes'] as $attrData) {
                                $variant->variantAttributes()
                                    ->updateOrCreate(
                                        ['attribute_id' => $attrData['attribute_id']],
                                        $attrData
                                    );
                            }
                        }
                    }
                }
            }
    
            $product->refresh();
    
            if ($oldTitle !== $product->title) {
                foreach ($product->variants as $variant) {
                    $variant->loadMissing(['variantAttributes.attribute', 'variantAttributes.option']);
                    $variant->update([
                        'slug' => $this->generateSlug($product, $variant)
                    ]);
                }
            }
    
            return $product->fresh()->load(
                'variants.variantAttributes.attribute',
                'variants.variantAttributes.option',
                'variants.variantImages'
            );
        });
    }

    public function deleteProduct($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
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

    private function generateSku($product, $variantData, $index): string
    {
        $prefix = strtoupper(Str::slug(substr($product->title, 0, 3)));
        $color = collect($variantData['attributes'])->firstWhere('attribute.code', 'color')['value'] ?? 'NA';
        $variantId = $variantData['id'] ?? $index;
        $productId = $product->id;
        return "{$prefix}-{$color}-{$variantId}-{$productId}";
    }

    private function generateSlug($product, $variant)
    {
        $productSlug = Str::slug($product->title ?? 'urun');
        $colorAttr = $variant->variantAttributes->firstWhere('attribute.code', 'color');
        $colorSlug = $colorAttr?->option?->slug ?? Str::slug($colorAttr?->value ?? 'renk-yok');
        return "{$colorSlug}-{$productSlug}-" . $variant->id;
    }
}
