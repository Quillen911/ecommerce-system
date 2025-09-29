<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Search\ElasticsearchService;
use App\Models\Product;

class IndexProductToElasticsearch implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $productId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = Product::with([
            'category.parent', 
            'variants.variantImages', 
            'variants.variantAttributes.attribute', 
            'variants.variantAttributes.option'
        ])->find($this->productId);

        // Ürün silinmişse job'u iptal et
        if (!$product) {
            return;
        }

        $data = $this->prepareElasticsearchData($product);

        $elasticsearchService = app(ElasticsearchService::class);
        $elasticsearchService->indexDocument('products', $product->id, $data);
    }

    /**
     * Elasticsearch için veriyi hazırla
     */
    private function prepareElasticsearchData(Product $product): array
    {
        $data = $product->toArray();
        $data['category_title'] = $product->category?->category_title ?? '';
        $data['total_stock_quantity'] = $product->getTotalStockQuantity(); // Düzeltilmiş method kullanımı
        $data['gender'] = $product->category?->parent?->category_title ?? '';

        $data['variants'] = $product->variants->map(function ($variant) {
            return [
                'id'             => $variant->id,
                'sku'            => $variant->sku,
                'price'          => $variant->price,
                'price_cents'    => $variant->price_cents,
                'stock_quantity' => $variant->stock_quantity,
                'images'         => $variant->variantImages->map(fn($image) => [
                    'id'    => $image->id,
                    'product_variant_id' => $image->product_variant_id,
                    'image' => asset('storage/productImages/' . $image->image),
                    'is_primary' => $image->is_primary,
                    'sort_order' => $image->sort_order
                ])->toArray(),
                'is_popular'     => $variant->is_popular,
                'attributes'     => $variant->variantAttributes->map(function ($attr) {
                    return [
                        'attribute_id' => $attr->attribute->id,
                        'code'         => $attr->attribute->code,
                        'name'         => $attr->attribute->name,
                        'value'        => $attr->option->value ?? null,
                        'slug'         => $attr->option->slug,
                    ];
                })->toArray()
            ];
        })->toArray();

        return $data;
    }

    /**
     * Job başarısız olduğunda
     */
    public function failed(\Throwable $exception): void
    {
        \Log::error('Elasticsearch indexing failed for product ID: ' . $this->productId, [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}