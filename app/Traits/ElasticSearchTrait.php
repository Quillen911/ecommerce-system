<?php

namespace App\Traits;

trait ElasticSearchTrait
{
    public function getCategoryFilterTrait(array $filters): array
    {
        if(isset($filters['category_title'])){
            return [[
                'match' => [
                    'category_title' => [
                        'query' => $filters['category_title'],
                        'operator' => 'or',
                        'fuzziness' => 'AUTO'
                    ]
                ]
            ]];
        }
        return [];
    }

    public function getPriceFilterTrait(array $filters): array
    {
        if(isset($filters['min_price']) || isset($filters['max_price'])) {
            $range = [];
            if (isset($filters['min_price'])) $range['gte'] = (float)$filters['min_price'];
            if (isset($filters['max_price'])) $range['lte'] = (float)$filters['max_price'];
            
            return [[
                'range' => ['list_price' => $range]
            ]];
        }
        return [];
    }

    public function getSortTrait(string $sorting = ''): array
    {
        if($sorting == 'price_asc' || $sorting == 'price_desc'){
            return [
                'list_price' => [
                    'order' => $sorting == 'price_asc' ? 'asc' : 'desc'
                ]
            ];
        }
        else if($sorting == 'stock_quantity_asc' || $sorting == 'stock_quantity_desc'){
            return [
                'stock_quantity' => [
                    'order' => $sorting == 'stock_quantity_asc' ? 'asc' : 'desc'
                ]
            ];
        }
        return [
            '_score' => ['order' => 'desc']
        ];
    }

    public function getStoreFilterTrait(array $filters): array
    {
        if(isset($filters['store_id'])){
            return [[
                'term' => [
                    'store_id' => $filters['store_id']
                ]
            ]];
        }
        return [];
    }

    public function mergeFiltersTrait(array $filters): array
    {
        return array_merge(
            $this->getCategoryFilterTrait($filters),
            $this->getPriceFilterTrait($filters),
            $this->getStoreFilterTrait($filters)
        );
    }
}