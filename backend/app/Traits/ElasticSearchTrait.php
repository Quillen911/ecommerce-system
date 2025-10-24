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
                    'category.id' => (int) $filters['category_id']
                ]
            ];
        }

        if (isset($filters['category_ids']) && is_array($filters['category_ids'])) {
            $query[] = [
                'terms' => [
                    'category.id' => array_map('intval', $filters['category_ids'])
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

        return $query;
    }
    public function getSortTrait(string $sorting = '', array $variantSortQuery = null): array
    {
        $nestedClause = [
            'path' => 'variants',
        ];

        if ($variantSortQuery !== null) {
            $nestedClause['filter'] = $variantSortQuery;
        } else {
            $nestedClause['filter'] = [
                'term' => ['variants.is_active' => true],
            ];
        }

        switch ($sorting) {
            case 'price_asc':
                return [[
                    'variants.price_cents' => [
                        'order'  => 'asc',
                        'mode'   => 'min',
                        'nested' => $nestedClause,
                    ],
                ]];
            case 'price_desc':
                return [[
                    'variants.price_cents' => [
                        'order'  => 'desc',
                        'mode'   => 'max',
                        'nested' => $nestedClause,
                    ],
                ]];
            case 'newest':
                return [['created_at' => ['order' => 'desc']]];
            case 'oldest':
                return [['created_at' => ['order' => 'asc']]];
            default:
                return ['_score' => ['order' => 'desc']];
        }
    }
}