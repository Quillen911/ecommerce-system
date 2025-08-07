<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Search\ElasticsearchService;

class DeleteProductToElasticsearch implements ShouldQueue
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

        $elasticsearchService->deleteDocument('products', $this->product['id']);
    }
}
