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
                'title' => ['type' => 'text', 'analyzer' => 'standard', 'fields' => ['keyword' => ['type' => 'keyword']]],
                'author' => ['type' => 'text', 'analyzer' => 'standard', 'fields' => ['keyword' => ['type' => 'keyword']]],
                'list_price' => ['type' => 'float'],
                'category_id' => ['type' => 'integer'],
                'category_title' => ['type' => 'text', 'analyzer' => 'standard', 'fields' => ['keyword' => ['type' => 'keyword']]],
                'stock_quantity' => ['type' => 'integer'],
                'created_at' => ['type' => 'date'],
                'updated_at' => ['type' => 'date'],
            ]
        ],
    ]
];