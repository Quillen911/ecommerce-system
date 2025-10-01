<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Search\ElasticsearchService;

class DeleteProductToElasticsearch implements ShouldQueue
{
    use Queueable;

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
        $elasticsearchService = app(ElasticsearchService::class);

        $elasticsearchService->deleteDocument('products', $this->productId);
    }
}
