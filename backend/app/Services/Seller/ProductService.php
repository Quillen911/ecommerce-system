<?php

namespace App\Services\Seller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Seller\Product\ProductStoreRequest;
use App\Http\Requests\Seller\Product\ProductUpdateRequest;
use App\Models\Category;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Repositories\Contracts\Store\StoreRepositoryInterface;
use Illuminate\Support\Str;

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

        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }

        return $this->productRepository->getProductsByStore($store->id);
    }
    
    public function createProduct($sellerId, array $request)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);

        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
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
        
        
        if (!$product) {
            throw new \Exception('Ürün oluşturulamadı');
        }
        
        return $product;
    }

    public function showProduct($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        
        $product = $this->productRepository->getProductByStore($store->id, $id);
        
        if (!$product) {
            throw new \Exception('Ürün bulunamadı');
        }
        
        return $product;
    }

    public function updateProduct($sellerId, array $request, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        $productData = array_merge($request, [
            'store_id' => $store->id,
            'store_name' => $store->name,
            'slug' => Str::slug($request['title']),
            'meta_title' => $this->generateMetaTitle($request, $store),
            'meta_description' => $request['meta_description'] ?? $this->generateMetaDescription($request, $store),
        ]);
        $result = $this->productRepository->updateProduct($productData, $store->id, $id);
        
        if (!$result) {
            throw new \Exception('Ürün güncellenemedi');
        }
        
        return $result;
    }

    public function deleteProduct($sellerId, $id)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }

        $result = $this->productRepository->deleteProduct($store->id, $id);
        
        if (!$result) {
            throw new \Exception('Ürün silinemedi');
        }
        
        return $result;
    }
    
    public function bulkStoreProduct(Request $request, $sellerId)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        
        $productsData = $request->input('products', []);
        $productsFiles = $request->file('products', []);
        
        if (empty($productsData)) {
            throw new \Exception('En az bir ürün eklemelisiniz');
        }
        
        $processedProducts = [];
        foreach ($productsData as $index => $productData) {
            if (isset($productsFiles[$index]['images'])) {
                $productData['images'] = $productsFiles[$index]['images'];
            }
            
            $processedProducts[] = array_merge($productData, [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'sold_quantity' => 0,
            ]);
        }
        
        $products = $this->productRepository->bulkCreateProducts($processedProducts);
        
        if (empty($products)) {
            throw new \Exception('Ürünler oluşturulamadı');
        }
        
        return $products;
    }

    public function bulkStoreProductApi(array $productsData, $sellerId)
    {
        $store = $this->storeRepository->getStoreBySellerId($sellerId);
        if(!$store){
            throw new \Exception('Mağaza bulunamadı');
        }
        
        if (empty($productsData)) {
            throw new \Exception('En az bir ürün eklemelisiniz');
        }
        
        $processedProducts = [];
        foreach ($productsData as $productData) {
            // API'den gelen base64 resimleri işle
            if (isset($productData['images']) && is_array($productData['images'])) {
                $processedImages = [];
                foreach ($productData['images'] as $imageData) {
                    if (is_string($imageData) && strpos($imageData, 'data:image') === 0) {
                        // Base64 resim verisi
                        $processedImages[] = $this->processBase64Image($imageData);
                    } else {
                        // Normal resim dosyası
                        $processedImages[] = $imageData;
                    }
                }
                $productData['images'] = $processedImages;
            }
            
            $processedProducts[] = array_merge($productData, [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'sold_quantity' => 0,
            ]);
        }
        
        $products = $this->productRepository->bulkCreateProducts($processedProducts);
        
        if (empty($products)) {
            throw new \Exception('Ürünler oluşturulamadı');
        }
        
        return $products;
    }

    private function processBase64Image($base64String)
    {
        // Base64 string'i parçala
        $parts = explode(',', $base64String);
        if (count($parts) !== 2) {
            throw new \Exception('Geçersiz base64 resim formatı');
        }
        
        $header = $parts[0];
        $base64Data = $parts[1];
        
        // Header'dan dosya uzantısını al
        if (preg_match('/data:image\/(\w+);base64/', $header, $matches)) {
            $extension = $matches[1];
        } else {
            $extension = 'jpg'; // Varsayılan
        }
        
        // Base64 verisini decode et
        $imageData = base64_decode($base64Data);
        if ($imageData === false) {
            throw new \Exception('Base64 decode hatası');
        }
        
        // Resim boyutunu kontrol et (2MB limit)
        if (strlen($imageData) > 2 * 1024 * 1024) {
            throw new \Exception('Resim boyutu 2MB\'dan büyük olamaz');
        }
        
        // Dosya adını oluştur
        $filename = time() . '_' . uniqid() . '.' . $extension;
        
        // Resmi storage'a kaydet
        \Storage::disk('public')->put('productsImages/' . $filename, $imageData);
        
        return $filename;
    }
    
    public function getCategories()
    {
        return $this->categoryRepository->all();
    }
    
    private function generateMetaTitle($request, $store)
    {
        $title = $request['title'];
        $author = $request['author'] ?? '';
        $storeName = $store->name ?? '';
        
        // Otomatik meta title oluştur
        $metaTitle = $title;
        
        if ($author) {
            $metaTitle .= " - {$author}";
        }
        
        if ($storeName) {
            $metaTitle .= " | {$storeName}";
        }
        
        // 60 karakter limiti (SEO için optimal)
        return strlen($metaTitle) > 60 ? substr($metaTitle, 0, 57) . '...' : $metaTitle;
    }
    
    private function generateMetaDescription($request, $store)
    {
        $title = $request['title'];
        $author = $request['author'] ?? '';
        $description = $request['description'] ?? '';
        $price = $request['list_price'] ?? 0;
        
        // Otomatik meta description oluştur
        $metaDescription = $title;
        
        if ($author) {
            $metaDescription .= " kitabını {$author} yazmıştır.";
        }
        
        if ($description) {
            // Açıklamadan ilk 100 karakteri al
            $shortDesc = strlen($description) > 100 ? substr($description, 0, 97) . '...' : $description;
            $metaDescription .= " {$shortDesc}";
        }
        
        if ($price > 0) {
            $metaDescription .= " En uygun fiyat: " . number_format($price, 2) . " TL.";
        }
        
        $metaDescription .= " Hızlı kargo, güvenli ödeme.";
        
        // 160 karakter limiti (SEO için optimal)
        return strlen($metaDescription) > 160 ? substr($metaDescription, 0, 157) . '...' : $metaDescription;
    }
}
