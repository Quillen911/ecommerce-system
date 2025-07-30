<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Facades\Log;

class ElasticsearchService
{
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

    public function searchProducts(string $query = '', array $filters = [], int $page = 1, int $size = 7 ): array
    {
        try{
            $from = ($page-1) * $size;
            $searchQuery = [];

            if(!empty($query)){
                $searchQuery['bool']['must'][] = [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title^2', 'author', 'description'],
                        'fuzziness' => 'AUTO'
                    ]
                ];
            }
            if(!empty($filters)){
                if(isset($filters['category_id'])){
                    $searchQuery['bool']['filter'][] = [
                        'term' => ['category_id' => $filters['category_id']]
                    ];
                }
                if(isset($filters['min_price']) || isset($filters['max_price'])) {
                    $range = [];
                    if (isset($filters['min_price'])) $range['gte'] = $filters['min_price'];
                    if (isset($filters['max_price'])) $range['lte'] = $filters['max_price'];
                    $searchQuery['bool']['filter'][] = [
                        'range' => ['list_price' => $range]
                    ];
                }
            }

            $params = [
                'index' => 'products',
                'body' => [
                    'query' => $searchQuery,
                    'size' => $size,
                    'from' => $from,
                    'sort' => [
                        '_score' => ['order' => 'desc']
                    ]
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
                                    'match' => [
                                        'title' => [
                                            'query' => $query,
                                            'fuzziness' => 'AUTO',
                                        ]                                            
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'size' => 7
                ]
            ];
            $response = $this->client->search($params);
            return $response['hits']['hits'];
        }catch(\Exception $e){
            Log::error("Autocomplete failed: {$e->getMessage()}");
            return [];
        }
    }

}
