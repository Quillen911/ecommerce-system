<?php

namespace App\Services\Seller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;

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
            $slug = Str::slug($request['title']);

            $productData = $request;
            $productData['store_id']      = $store->id;
            $productData['store_name']    = $store->name;
            $productData['sold_quantity'] = 0;
            $productData['slug']          = $slug;
            $productData['is_published']  = true;
            $productData['meta_title']    = $this->generateMetaTitle($productData, $store);
            $productData['meta_description'] = $productData['meta_description'] 
                ?? $this->generateMetaDescription($productData, $store);

            $product = $this->productRepository->createProduct($productData);

            if(isset($request['images'])){
                if ($request['images']) {
                    foreach ($request['images'] as $file) {
                        $product->images()->create([
                            'image' => $file,
                        ]);
                    }
                }
            }
            $totalStock = 0;

            foreach ($request['variants'] as $index => $variantData) {
                $sku = $this->generateSku($product, $variantData, $index);

                $variant = $product->variants()->create([
                    'sku'            => $sku,
                    'price'          => $variantData['price'],
                    'price_cents'    => $variantData['price'] * 100,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                ]);

                $totalStock += $variantData['stock_quantity'] ?? 0;

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
            }

            $product->update([
                'stock_quantity' => $totalStock,
                'slug' => $slug . '-p-' . $product->id,
            ]);

            return $product->load([
                    'images',
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

            $productData = $request;
            $productData['store_id'] = $store->id;
            $productData['store_name'] = $store->name;

            if (isset($request['title'])) {
                $productData['slug'] = Str::slug($request['title'] . '-p-' . $product->id);
                $productData['meta_title'] = $this->generateMetaTitle($request, $store);
            }

            if (isset($request['meta_description'])) {
                $productData['meta_description'] = $request['meta_description'];
            }

            /**
             * Ürün resimleri
             */
            $uploadedProductFiles = request()->file('images') ?? []; // yeni dosyalar
            $existingProductImages = is_array($product->images) ? $product->images : [];
            $mergedProductImages = array_merge($existingProductImages, $uploadedProductFiles);
            $productData['images'] = $this->processImages($mergedProductImages);

            $this->productRepository->updateProduct($productData, $store->id, $id);

            $product->refresh();
            $totalStock = 0;

            /**
             * Varyant güncelleme
             */
            if (isset($request['variants'])) {
                foreach ($request['variants'] as $variantData) {
                    $variantImages = Arr::flatten($variantData['images'] ?? []);

                    if (isset($variantData['id'])) {
                        $variant = $product->variants()->find($variantData['id']);
                        if ($variant) {
                            $updateData = [];
                    
                            if (isset($variantData['price'])) {
                                $updateData['price'] = $variantData['price'];
                                $updateData['price_cents'] = $variantData['price'] * 100;
                            }
                    
                            if (isset($variantData['stock_quantity'])) {
                                $updateData['stock_quantity'] = $variantData['stock_quantity'];
                            }
                    
                            if (isset($variantData['images'])) {
                                $updateData['images'] = $this->processImages($variantData['images']);
                            }
                    
                            try {
                                $variant->update($updateData);
                            } catch (\Exception $e) {
                                \Log::error("Variant update failed", [
                                    'error' => $e->getMessage(),
                                    'data' => $updateData
                                ]);
                            }

                            $variant->refresh();
                    
                            $totalStock += $variant->stock_quantity;
                            if (isset($variantData['attributes'])) {
                                if (!empty($variantData['attributes'])) {
                                    foreach ($variantData['attributes'] as $attr) {
                                        $variant->variantAttributes()->updateOrCreate(
                                            ['attribute_id' => $attr['attribute_id']],
                                            ['option_id' => $attr['option_id']]
                                        );
                                    }
                                }
                            }
                        } else {
                            throw new \Exception("Varyant bulunamadı: {$variantData['id']}");
                        }
                    }
                }
            }

            $product->update(['stock_quantity' => $totalStock]);

            return $product->fresh()->load(
                'variants.variantAttributes.attribute',
                'variants.variantAttributes.option'
            );
        });
    }



    public function deleteProduct($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        return $this->productRepository->deleteProduct($store->id, $id);
    }

    private function processImages($images): array
    {
        $processed = [];

        foreach ($images as $image) {
            if ($image instanceof \Illuminate\Http\UploadedFile) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('productImages', $filename, 'public');

                if ($path) {
                    $processed[] = $filename;
                }
            } elseif (is_string($image)) {
                $processed[] = $image;
            } else {
                \Log::warning("Unsupported image type", ['image' => $image]);
            }
        }

        return $processed;
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

        if ($author) {
            $metaDescription .= " kitabını {$author} yazmıştır.";
        }

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
        $prefix = strtoupper(Str::slug(substr($product->title, 0, 3))); // Ürün başlığından 3 harf
        $attrs = collect($variantData['attributes'] ?? [])
            ->map(fn($a) => strtoupper(Str::slug($a['option_id'])))
            ->implode('-'); // Örn: MAV-5Y
        $productId = $product->id;
        return $prefix . '-' . $attrs . '-' . $productId;
    }
}
