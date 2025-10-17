<?php

namespace App\Services\Search;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Facades\Log;
use App\Traits\ElasticSearchTrait;

class ElasticsearchService
{
    use ElasticSearchTrait;
    private Client $client;

    public function __construct()
    {
        $hosts = config('elasticsearch.hosts', ['localhost:9200']);
        $this->client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }
    
    public function createIndex(string $indexName, array $mapping = []): bool
    {

        try{
            $params = [
                'index' => $indexName,
                'body' => [
                    'mappings' => $mapping
                ]
            ];
            $response = $this->client->indices()->create($params);
            if($response){
                Log::info("Index created {$indexName}: {$response}");
                return true;
            }
            Log::error("Index creation failed {$indexName}: {$response}");
            return false;
        }catch(\Exception $e){
            Log::error("Index creation failed {$indexName}: {$e->getMessage()}");
            return false;
        }

    }
    

    public function indexDocument(string $indexName, string $id, array $document): bool
    {
        try{
            $params = [
                'index' => $indexName,
                'id' => $id,
                'body' => $document
            ];
            $response = $this->client->index($params);
            Log::info("Document indexed {$indexName}{$id}: {$response}");
            return true;

        }catch(\Exception $e){
            Log::error("Document indexing failed {$indexName}{$id}: {$e->getMessage()}");
            return false;
        }
    }
    public function deleteDocument(string $indexName, string $id): bool
    {
        try {
            $params = [
                'index' => $indexName,
                'id' => $id,
            ];
            $response = $this->client->delete($params);
            Log::info("Document deleted {$indexName}{$id}: {$response}");
            return true;

        } catch(\Exception $e){
            Log::error("Document deletion failed {$indexName}{$id}: {$e->getMessage()}");
            return false;
        }
    }

    public function searchProducts(?string $query = '', array $filters = [], string $sorting = '', int $page = 1, int $size = 7): array
    {
        try{
            $from = ($page-1) * $size;
            $searchQuery = [];
            //search query
            if (!empty($query)) {
                $searchQuery['bool']['must'][] = [
                    'bool' => [
                        'should' => [
                            [
                                'multi_match' => [
                                    'query'     => $query,
                                    'fields'    => ['title^3', 'category_title^2', 'gender^2'],
                                    'fuzziness' => 'AUTO'
                                ]
                            ],
                            [
                                'nested' => [
                                    'path'  => 'variants',
                                    'query' => [
                                        'multi_match' => [
                                            'query'  => $query,
                                            'fields' => ['variants.sku', 'variants.color_name^2'],
                                            'fuzziness' => 'AUTO'
                                        ]
                                    ]
                                ]
                            ],
                        ]
                    ]
                ];
            } else {
                $searchQuery['bool']['filter'][] = [
                    'nested' => [
                        'path' => 'variants',
                        'query' => [
                            'term' => [
                                'variants.is_popular' => true
                            ]
                        ]
                    ]
                ];
            }

            $categoryFilters = $this->getCategoryFilterTrait($filters);
            
            $variantMustQueries = [];
            
            if(isset($filters['min_price']) || isset($filters['max_price'])) {
                $range = [];
                if (isset($filters['min_price'])) $range['gte'] = (int)($filters['min_price'] * 100);
                if (isset($filters['max_price'])) $range['lte'] = (int)($filters['max_price'] * 100);
                
                $variantMustQueries[] = [
                    'range' => ['variants.price_cents' => $range]
                ];
            }
            
            if (isset($filters['sizes'])) {
                $sizes = is_array($filters['sizes']) ? $filters['sizes'] : explode(',', $filters['sizes']);
                $variantMustQueries[] = [
                    'nested' => [
                        'path' => 'variants.sizes',
                        'query' => [
                            'nested' => [
                                'path' => 'variants.sizes.size_option',
                                'query' => [
                                    'terms' => [
                                        'variants.sizes.size_option.slug' => $sizes
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
            
            if (isset($filters['color'])) {
                $colors = is_array($filters['color']) ? $filters['color'] : explode(',', $filters['color']);
                $variantMustQueries[] = [
                    'terms' => ['variants.color_code' => $colors]
                ];
            }
            
            $variantQuery = empty($variantMustQueries) 
                ? ['match_all' => new \stdClass()]
                : ['bool' => ['must' => $variantMustQueries]];

            $sortArray = $this->getSortTrait($sorting);
            
            $boolQuery = [
                'filter' => array_merge(
                    $categoryFilters,
                    [[
                        'nested' => [
                            'path' => 'variants',
                            'query' => $variantQuery,
                            'inner_hits' => [
                                'size' => 100
                            ]
                        ]
                    ]]
                )
            ];
            
            if (!empty($searchQuery['bool']['must'])) {
                $boolQuery['must'] = $searchQuery['bool']['must'];
            }
            
            $params = [
                'index' => 'products',
                'body' => [
                    'query' => [
                        'bool' => $boolQuery
                    ],
                    'size' => $size,
                    'from' => $from,
                    'sort' => $sortArray              
                ]
            ];
            
            $response = $this->client->search($params);
            return [
                'hits' => $response['hits']['hits'],
                'total' => $response['hits']['total']['value'],
                'page' => $page,
                'size' => $size
            ];

        } catch(\Exception $e){
            Log::error("Product search failed", ['error' => $e->getMessage()]);
            return [
                'hits' => [],
                'total' => 0,
                'page' => $page,
                'size' => $size
            ];
        }
    }

    public function filterProducts(array $filters = [], string $sorting = '', int $page = 1, int $size = 7 ): array
    {
        try {
            $from = ($page-1) * $size;
            
            $categoryFilters = $this->getCategoryFilterTrait($filters);
            
            $variantMustQueries = [];
            
            if(isset($filters['min_price']) || isset($filters['max_price'])) {
                $range = [];
                if (isset($filters['min_price'])) $range['gte'] = (int)($filters['min_price'] * 100);
                if (isset($filters['max_price'])) $range['lte'] = (int)($filters['max_price'] * 100);
                
                $variantMustQueries[] = [
                    'range' => ['variants.price_cents' => $range]
                ];
            }
            
            if (isset($filters['sizes'])) {
                $sizes = is_array($filters['sizes']) ? $filters['sizes'] : explode(',', $filters['sizes']);
                $variantMustQueries[] = [
                    'nested' => [
                        'path' => 'variants.sizes',
                        'query' => [
                            'nested' => [
                                'path' => 'variants.sizes.size_option',
                                'query' => [
                                    'terms' => [
                                        'variants.sizes.size_option.slug' => $sizes
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
            
            if (isset($filters['color'])) {
                $colors = is_array($filters['color']) ? $filters['color'] : explode(',', $filters['color']);
                $variantMustQueries[] = [
                    'terms' => ['variants.color_code' => $colors]
                ];
            }
            
            $variantQuery = empty($variantMustQueries) 
                ? ['match_all' => new \stdClass()]
                : ['bool' => ['must' => $variantMustQueries]];
            
            $sortArray = $this->getSortTrait($sorting);
            
            $params = [
                'index' => 'products',
                'body' => [
                    'query' => [
                        'bool' => [
                            'filter' => array_merge(
                                $categoryFilters,
                                [[
                                    'nested' => [
                                        'path' => 'variants',
                                        'query' => $variantQuery,
                                        'inner_hits' => [
                                            'size' => 100
                                        ]
                                    ]
                                ]]
                            )
                        ]
                    ],
                    'size' => $size,
                    'from' => $from,
                    'sort' => $sortArray              
                ]
            ];
            $response = $this->client->search($params);
            Log::info('ES Query Params', $params);
            return [
                'hits' => $response['hits']['hits'],
                'total' => $response['hits']['total']['value'],
                'page' => $page,
                'size' => $size
            ];
        } catch(\Exception $e){
            Log::error("Ürün filtreleme başarısız", ['error' => $e->getMessage()]);
            return [
                'hits' => [],
                'total' => 0,
                'page' => $page,
                'size' => $size
            ];
        }
    }

    public function sortProducts(string $sorting = '', int $page = 1, int $size = 7 ): array
    {
        try{
            $from = ($page-1) * $size;
            $searchQuery = [
                'match_all' => new \stdClass()
            ];
            
            $sortArray = $this->getSortTrait($sorting);
            
            $params = [
                'index' => 'products',
                'body' => [
                    'query' => $searchQuery,
                    'size' => $size,
                    'from' => $from,
                    'sort' => $sortArray,
                ]
            ];
            
            $response = $this->client->search($params);
            return [
                'hits' => $response['hits']['hits'],
                'total' => $response['hits']['total']['value'],
                'page' => $page,
                'size' => $size
            ];
        }catch(\Exception $e){
            Log::error("Sorting failed: {$e->getMessage()}");
            return [];
        }
    }

    public function autocomplete(string $query): array
    {
        try{
            $params = [
                'index' => 'products',
                'body' => [
                    'query' => [
                        'bool' => [
                            'should' => [
                                [
                                    'prefix' => [
                                        'title.keyword' => strtolower($query)
                                    ]
                                ],
                                [
                                    'prefix' => [
                                        'author.keyword' => strtolower($query)
                                    ]
                                ],
                                [
                                    'match_phrase_prefix' => [
                                        'author.keyword' => strtolower($query)
                                    ]
                                ],

                                [
                                    'match' => [
                                        'category_title.keyword' => strtolower($query)
                                    ]
                                ],
                                [
                                    'multi_match' => [
                                        'query' => $query,
                                        'fields' => ['title^3', 'author^2', 'category_title^2'],
                                        'fuzziness' => 'AUTO'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'size' => 7,
                    '_source' => [
                        'id', 'title', 'author', 'images', 'list_price', 'store_name', 'category_title'
                    ]
                ]
            ];
            $response = $this->client->search($params);
            return $response['hits']['hits'];
        }catch(\Exception $e){
            Log::error("Autocomplete failed: {$e->getMessage()}");
            return [];
        }
    }

    public function updateMapping(): bool
    {
        try {
            $indexName = 'products';

            if ($this->client->indices()->exists(['index' => $indexName])) {
                $this->client->indices()->delete(['index' => $indexName]);
            }

            $params = [
                'index' => $indexName,
                'body' => [
                    'settings' => config('elasticsearch.settings'),
                    'mappings' => [
                        'properties' => config('elasticsearch.mappings')['products']['properties']
                    ],
                ]
            ];

            $this->client->indices()->create($params);

            Log::info("Elasticsearch mapping updated successfully with autocomplete analyzer");
            return true;
        } catch (\Exception $e) {
            Log::error("Elasticsearch mapping update failed: " . $e->getMessage());
            return false;
        }
    }

    
}
