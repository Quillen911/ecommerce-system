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
                'title' => ['type' => 'text', 'analyzer' => 'standard'],
                'author' => ['type' => 'text', 'analyzer' => 'standard'],
                'list_price' => ['type' => 'float'],
                'category_id' => ['type' => 'integer'],
                'stock_quantity' => ['type' => 'integer'],
                'created_at' => ['type' => 'date'],
                'updated_at' => ['type' => 'date'],
            ]
        ]
    ]
];