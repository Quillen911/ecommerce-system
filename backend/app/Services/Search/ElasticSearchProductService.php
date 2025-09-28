<?php

namespace App\Services\Search;

use App\Services\Search\ElasticsearchService;

class ElasticSearchProductService
{
    protected ElasticsearchService $elasticSearch;
    public function __construct(ElasticsearchService $elasticSearch)
    {
        $this->elasticSearch = $elasticSearch;
    }

    public function searchProducts($query, $filters, $sorting, $page = 1, $size = 12)
    {
        $results = $this->elasticSearch->searchProducts($query, $filters, $sorting, $page, $size);
        return [
            'products' => collect($results['hits'])->pluck('_source')->toArray(),
            'results' => $results
        ];
    }

    public function filterProducts($filters, $page = 1, $size = 12)
    {
        $results = $this->elasticSearch->filterProducts($filters, $page, $size);
        return [
            'products' => collect($results['hits'])->pluck('_source')->toArray(),
            'total' => $results['total'],
            'results' => $results
        ];
    }

    public function sortingProducts($sorting, $page = 1, $size = 12)
    {
        $results = $this->elasticSearch->sortProducts($sorting, $page, $size);
        return [
            'products' => collect($results['hits'])->pluck('_source')->toArray(),
            'results' => $results
        ];
    }

    public function autocomplete($query)
    {
        $results = $this->elasticSearch->autocomplete($query);
        return [
            'products' => collect($results)->pluck('_source')->toArray(),
            'results' => $results
        ];
    }
}