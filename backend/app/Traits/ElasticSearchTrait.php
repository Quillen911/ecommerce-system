<?php

namespace App\Traits;

trait ElasticSearchTrait
{

    public function getCategoryFilterTrait(array $filters): array
    {
        $query = [];

        if (isset($filters['category_id'])) {
            $query[] = [
                'term' => [
                    'category_id' => (int) $filters['category_id']
                ]
            ];
        }

        if (isset($filters['category_ids']) && is_array($filters['category_ids'])) {
            $query[] = [
                'terms' => [
                    'category_id' => array_map('intval', $filters['category_ids'])
                ]
            ];
        }

        if (isset($filters['child_category_ids']) && is_array($filters['child_category_ids'])) {
            $query[] = [
                'terms' => [
                    'category_id' => array_map('intval', $filters['child_category_ids'])
                ]
            ];
        }

        if (isset($filters['gender'])) {
            $query[] = [
                'term' => [
                    'gender.keyword' => $filters['gender']
                ]
            ];
        }

        $attributeFilters = [];

        foreach (['color', 'age'] as $attr) {
            if (isset($filters[$attr])) {
                $values = is_array($filters[$attr]) 
                ? $filters[$attr] 
                : explode(',', $filters[$attr]);
                
                $attributeFilters[] = [
                    'nested' => [
                        'path' => 'variants.attributes',
                        'query' => [
                            'bool' => [
                                'must' => [
                                    ['term' => ['variants.attributes.code' => $attr]],
                                    ['terms' => ['variants.attributes.slug' => $values]]
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }
        
        if (!empty($attributeFilters)) {
            $query[] = [
                'nested' => [
                    'path' => 'variants',
                    'query' => [
                        'bool' => [
                            'must' => $attributeFilters
                        ]
                    ],
                    'inner_hits' => [
                        '_source' => true
                    ]
                ]
            ];
        }
        return $query;
    }

    public function getPriceFilterTrait(array $filters): array
    {
        if(isset($filters['min_price']) || isset($filters['max_price'])) {
            $range = [];
            if (isset($filters['min_price'])) $range['gte'] = (float)$filters['min_price'];
            if (isset($filters['max_price'])) $range['lte'] = (float)$filters['max_price'];
            
            return [[
                'nested' => [
                    'path' => 'variants',
                    'query' => [
                        'range' => ['variants.price' => $range]
                    ]
                ]
            ]];
        }
        return [];
    }

    public function getSortTrait(string $sorting = ''): array
    {
        if($sorting == 'price_asc' || $sorting == 'price_desc'){
            return [[
                'variants.price' => [
                    'order' => $sorting == 'price_asc' ? 'asc' : 'desc',
                    'mode'  => 'min',
                    'nested' => [
                        'path' => 'variants'
                    ]
                ]
            ]];
        }
        else if($sorting == 'stock_quantity_asc' || $sorting == 'stock_quantity_desc'){
            return [[
                'variants.stock_quantity' => [
                    'order' => $sorting == 'stock_quantity_asc' ? 'asc' : 'desc',
                    'mode'  => 'sum',
                    'nested' => [
                        'path' => 'variants'
                    ]
                ]
            ]];
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
            $this->getStoreFilterTrait($filters),

        );
    }
}