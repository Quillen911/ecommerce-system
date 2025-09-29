<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\Search\ElasticsearchService;

class ReindexProducts extends Command
{
    protected $signature = 'app:reindex-products';
    protected $description = 'Reindex all products into Elasticsearch';

    public function handle()
    {
        $service = app(ElasticsearchService::class);

        Product::with([
            'category', 
            'variants.variantImages', 
            'variants.variantAttributes.attribute', 
            'variants.variantAttributes.option'
        ])->chunk(100, function ($products) use ($service) {
            foreach ($products as $product) {
                $data = $this->prepareElasticsearchData($product);
                $service->indexDocument('products', $product->id, $data);
            }
        });

        $this->info('Products reindexed successfully');
    }

    private function prepareElasticsearchData(Product $product): array
    {
        $data = $product->toArray();
        $data['category_title'] = $product->category?->category_title ?? '';
        $data['total_stock_quantity'] = $product->getTotalStockQuantity();
        $data['gender'] = $product->category?->parent?->category_title ?? '';
        
        $data['variants'] = $product->variants->map(function ($variant) {
            return [
                'id'             => $variant->id,
                'sku'            => $variant->sku,
                'price'          => $variant->price,
                'price_cents'    => $variant->price_cents,
                'stock_quantity' => $variant->stock_quantity,
                'images'         => $variant->variantImages->map(fn($image) => [
                    'id'                => $image->id,
                    'product_variant_id'=> $image->product_variant_id,
                    'image'             => asset('storage/productImages/' . $image->image),
                    'is_primary'        => $image->is_primary,
                    'sort_order'        => $image->sort_order
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
}
