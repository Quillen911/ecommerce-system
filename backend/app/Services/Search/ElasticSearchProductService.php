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
        if ($query) {
            $lowerQuery = mb_strtolower($query, 'UTF-8');
    
            if (str_contains($lowerQuery, 'kız çocuk')) {
                $filters['gender'] = 'Kız Çocuk';
                $query = trim(str_ireplace('kız çocuk', 'çocuk', $query));
            } elseif (str_contains($lowerQuery, 'erkek çocuk')) {
                $filters['gender'] = 'Erkek Çocuk';
                $query = trim(str_ireplace('erkek çocuk', 'çocuk', $query));
            }
        }
    
        $results = $this->elasticSearch->searchProducts($query, $filters, $sorting, $page, $size);
    
        return [
            'products' => collect($results['hits'])->pluck('_source')->toArray(),
            'results'  => $results
        ];
    }

    public function filterProducts($filters, $sorting, $page = 1, $size = 12)
    {
        $results = $this->elasticSearch->filterProducts($filters, $sorting, $page, $size);

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