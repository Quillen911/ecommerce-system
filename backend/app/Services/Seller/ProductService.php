<?php

namespace App\Services\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductVariant;
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
            $productData = array_merge($request, [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'sold_quantity' => 0,
                'is_published' => true,
                'slug' => Str::slug($request['title']),
                'meta_title' => $this->generateMetaTitle($request, $store),
                'meta_description' => $request['meta_description'] ?? $this->generateMetaDescription($request, $store),
            ]);

            $product = $this->productRepository->createProduct($productData);

            if (!empty($request['variants'])) {
                foreach ($request['variants'] as $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'price_cents' => $variantData['price'] * 100,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'images' => $this->processImages($variantData['images'] ?? []),
                    ]);

                    if (!empty($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attr) {
                            $variant->variantAttributes()->create([
                                'attribute_id' => $attr['attribute_id'],
                                'option_id' => $attr['option_id'] ?? null,
                                'value' => $attr['value'] ?? null,
                            ]);
                        }
                    }
                }
            }

            return $product->load('variants.variantAttributes.attribute', 'variants.variantAttributes.option');
        });
    }

    public function showProduct($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        $product = $this->productRepository->getProductByStore($store->id, $id);

        if (!$product) {
            throw new \Exception('Ürün bulunamadı');
        }

        return $product->load('variants.variantAttributes.attribute', 'variants.variantAttributes.option');
    }

    public function showProductBySlug($sellerId, $slug)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if (!$store) {
            throw new \Exception('Mağaza bulunamadı');
        }

        $product = $this->productRepository->getProductBySlug($store->id, $slug);
        if (!$product) {
            throw new \Exception('Ürün bulunamadı');
        }

        return $product->load('variants.variantAttributes.attribute', 'variants.variantAttributes.option');
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
            
            $productData = array_merge($request, [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'slug' => Str::slug($request['title'] ?? $product->title),
                'meta_title' => $this->generateMetaTitle($request, $store),
                'meta_description' => $request['meta_description'] ?? $this->generateMetaDescription($request, $store),
            ]);
            
            $this->productRepository->updateProduct($productData, $store->id, $id);
            
            $product->refresh();

            // Varyantlar güncelle
            if (isset($request['variants'])) {
                $existingVariantIds = $product->variants()->pluck('id')->toArray();
                $incomingVariantIds = collect($request['variants'])->pluck('id')->filter()->toArray();

                // Silinecek varyantlar
                $toDelete = array_diff($existingVariantIds, $incomingVariantIds);
                if (!empty($toDelete)) {
                    ProductVariant::whereIn('id', $toDelete)->delete();
                }

                foreach ($request['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        // Güncelle
                        $variant = $product->variants()->find($variantData['id']);
                        if ($variant) {
                            $variant->update([
                                'sku' => $variantData['sku'] ?? $variant->sku,
                                'price' => $variantData['price'] ?? $variant->price,
                                'price_cents' => ($variantData['price'] ?? $variant->price) * 100,
                                'stock_quantity' => $variantData['stock_quantity'] ?? $variant->stock_quantity,
                                'images' => $this->processImages($variantData['images'] ?? $variant->images),
                            ]);

                            // Attribute update
                            $variant->variantAttributes()->delete();
                            if (!empty($variantData['attributes'])) {
                                foreach ($variantData['attributes'] as $attr) {
                                    $variant->variantAttributes()->create([
                                        'attribute_id' => $attr['attribute_id'],
                                        'option_id' => $attr['option_id'] ?? null,
                                        'value' => $attr['value'] ?? null,
                                    ]);
                                }
                            }
                        }
                    } else {
                        // Yeni varyant ekle
                        $newVariant = $product->variants()->create([
                            'sku' => $variantData['sku'],
                            'price' => $variantData['price'],
                            'price_cents' => $variantData['price'] * 100,
                            'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                            'images' => $this->processImages($variantData['images'] ?? []),
                        ]);

                        if (!empty($variantData['attributes'])) {
                            foreach ($variantData['attributes'] as $attr) {
                                $newVariant->variantAttributes()->create([
                                    'attribute_id' => $attr['attribute_id'],
                                    'option_id' => $attr['option_id'] ?? null,
                                    'value' => $attr['value'] ?? null,
                                ]);
                            }
                        }
                    }
                }
            }
            
            return $product->fresh()->load('variants.variantAttributes.attribute', 'variants.variantAttributes.option');
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

    private function processImages($images)
    {
        $processedImages = [];
        foreach ($images as $image) {
            if (is_string($image) && strpos($image, 'data:image') === 0) {
                $processedImages[] = $this->processBase64Image($image);
            } elseif ($image instanceof \Illuminate\Http\UploadedFile) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('productsImages', $filename, 'public');
                $processedImages[] = $filename;
            } else {
                $processedImages[] = $image;
            }
        }
        return $processedImages;
    }

    private function processBase64Image($base64String)
    {
        $parts = explode(',', $base64String);
        if (count($parts) !== 2) {
            throw new \Exception('Geçersiz base64 resim formatı');
        }

        $header = $parts[0];
        $base64Data = $parts[1];

        if (preg_match('/data:image\/(\w+);base64/', $header, $matches)) {
            $extension = $matches[1];
        } else {
            $extension = 'jpg';
        }

        $imageData = base64_decode($base64Data);
        if ($imageData === false) {
            throw new \Exception('Base64 decode hatası');
        }

        if (strlen($imageData) > 2 * 1024 * 1024) {
            throw new \Exception('Resim boyutu 2MB\'dan büyük olamaz');
        }

        $filename = time() . '_' . uniqid() . '.' . $extension;
        Storage::disk('public')->put('productsImages/' . $filename, $imageData);

        return $filename;
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
}
