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

        $allVariants = collect($results['hits'])->flatMap(function ($hit) {
            $source = $hit['_source'];

            if (isset($hit['inner_hits']['variants']['hits']['hits'])) {
                $source['variants'] = collect($hit['inner_hits']['variants']['hits']['hits'])
                    ->pluck('_source')
                    ->toArray();
            }

            return collect($source['variants'])->map(function ($variant) use ($source) {
                return [
                    'variant' => $variant,
                    'product' => [
                        'id' => $source['id'],
                        'store_id' => $source['store_id'],
                        'title' => $source['title'],
                        'slug' => $source['slug'],
                        'category' => $source['category'] ?? null,
                        'description' => $source['description'] ?? null,
                        'meta_title' => $source['meta_title'] ?? null,
                        'meta_description' => $source['meta_description'] ?? null,
                        'is_published' => $source['is_published'] ?? null,
                    ]
                ];
            });
        });
        if ($sorting === 'price_asc') {
            $allVariants = $allVariants->sortBy('variant.price_cents')->values();
        } elseif ($sorting === 'price_desc') {
            $allVariants = $allVariants->sortByDesc('variant.price_cents')->values();
        }

        return [
            'products' => $allVariants->toArray(),
            'total' => $allVariants->count(),
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