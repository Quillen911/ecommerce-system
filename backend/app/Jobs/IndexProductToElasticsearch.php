<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Search\ElasticsearchService;
use App\Models\Product;
use App\Services\Search\ProductIndexerService;

class IndexProductToElasticsearch implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    public function handle(): void
    {
        $product = Product::with([
            'category.parent', 
            'variants.variantImages', 
            'variants.variantAttributes.attribute', 
            'variants.variantAttributes.option'
        ])->find($this->productId);

        if (!$product) {
            return;
        }
        $indexerService = app(ProductIndexerService::class);
        $data = $indexerService->prepare($product);

        $elasticsearchService = app(ElasticsearchService::class);
        $elasticsearchService->indexDocument('products', $product->id, $data);
    }
    
    public function failed(\Throwable $exception): void
    {
        \Log::error('Elasticsearch indexing failed for product ID: ' . $this->productId, [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}