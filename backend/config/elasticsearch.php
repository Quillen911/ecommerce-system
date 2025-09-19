<?php

return [
    'hosts' => [
        'http://elasticsearch:9200',
    ],
    
    'connection' => [
        'timeout' => env('ELASTICSEARCH_TIMEOUT', 30),
        'retries' => env('ELASTICSEARCH_RETRIES', 3),
    ],
    
    'mappings' => [
        'products' => [
            'properties' => [
                'id' => ['type' => 'integer'],
                'store_id' => ['type' => 'integer'],
                'store_name' => ['type' => 'text', 'analyzer' => 'standard', 'fields' => ['keyword' => ['type' => 'keyword']]],
                'title' => ['type' => 'text', 'analyzer' => 'standard', 'fields' => ['keyword' => ['type' => 'keyword']]],
                'list_price' => ['type' => 'float'],
                'category_id' => ['type' => 'integer'],
                'computed_attributes' => [
                    'type' => 'nested',
                    'properties' => [
                        'code' => ['type' => 'keyword'],
                        'label' => ['type' => 'text'],
                        'value' => ['type' => 'keyword'],
                        'slug' => ['type' => 'keyword']
                    ]
                ],
                'category_title' => ['type' => 'text', 'analyzer' => 'standard', 'fields' => ['keyword' => ['type' => 'keyword']]],
                'stock_quantity' => ['type' => 'integer'],
                'sold_quantity' => ['type' => 'integer'],
                'images' => ['type' => 'keyword'],
                'created_at' => ['type' => 'date'],
                'updated_at' => ['type' => 'date'],
            ]
        ],
    ]
];