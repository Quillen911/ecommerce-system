<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\Search\ElasticsearchService;
use App\Services\Search\ProductIndexerService;

class ReindexProducts extends Command
{
    protected $signature = 'app:reindex-products';
    protected $description = 'Reindex all products into Elasticsearch';

    public function handle()
    {
        $service = app(ElasticsearchService::class);
        $indexer = app(ProductIndexerService::class);
    
        Product::with([
            'category.parent',
            'category.gender',
            'variants.variantImages',
            'variants.variantSizes.sizeOption',
            'variants.variantSizes.inventory'
        ])->chunk(100, function ($products) use ($service, $indexer) {
            foreach ($products as $product) {
                $data = $indexer->prepare($product);
                $service->indexDocument('products', $product->id, $data);
            }
        });
    
        $this->info('Products reindexed successfully');
    }
}
