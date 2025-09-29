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

        $products = collect($results['hits'])->map(function ($hit) {
            $source = $hit['_source'];

            if (isset($hit['inner_hits']['variants']['hits']['hits'])) {
                $source['variants'] = collect($hit['inner_hits']['variants']['hits']['hits'])
                    ->pluck('_source')
                    ->toArray();
            }

            return $source;
        })->toArray();

        return [
            'products' => $products,
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