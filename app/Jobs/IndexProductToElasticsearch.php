<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Search\ElasticsearchService;

class IndexProductToElasticsearch implements ShouldQueue
{
    use Queueable;

    protected $product;

    /**
     * Create a new job instance.
     */
    public function __construct(array $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $elasticsearchService = app(ElasticsearchService::class);

        $data = $this->product;

        $data['list_price'] = (float) $data['list_price'];
        $data['category_title'] = $data['category_title'] ?? '';

        $elasticsearchService->indexDocument('products', $this->product['id'], $data);

    }
}
