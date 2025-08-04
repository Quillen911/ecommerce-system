<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\Search\ElasticsearchService;           

class ReindexProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reindex-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(ElasticsearchService::class);

        Product::chunk(100, function ($products) use ($service) {
            foreach ($products as $product) {
                $data = $product->toArray();
                $data['category_title'] = $product->category?->category_title ?? '';
                $data['list_price'] = (float) $data['list_price'];

                $service->indexDocument('products', $product->id, $data);
            }
        });
        $this->info('Products reindexed successfully');
    }
}
