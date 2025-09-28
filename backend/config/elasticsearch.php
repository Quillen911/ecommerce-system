<?php

return [
    'hosts' => [
        'http://elasticsearch:9200',
    ],

    'connection' => [
        'timeout' => env('ELASTICSEARCH_TIMEOUT', 30),
        'retries' => env('ELASTICSEARCH_RETRIES', 3),
    ],

    'settings' => [
        'analysis' => [
            'filter' => [
                'synonym_filter' => [
                    'type' => 'synonym',
                    'synonyms' => [
                        'lol, league of legends'
                    ]
                ]
            ],
            'analyzer' => [
                'autocomplete_analyzer' => [
                    'tokenizer' => 'autocomplete_tokenizer',
                    'filter' => ['lowercase', 'synonym_filter']
                ],
                'autocomplete_search_analyzer' => [
                    'tokenizer' => 'standard',
                    'filter' => ['lowercase', 'synonym_filter']
                ],
            ],
            'tokenizer' => [
                'autocomplete_tokenizer' => [
                    'type' => 'edge_ngram',
                    'min_gram' => 2,
                    'max_gram' => 20,
                    'token_chars' => ['letter', 'digit']
                ]
            ]
        ]
    ],

    'mappings' => [
        'products' => [
            'properties' => [
                'id' => ['type' => 'integer'],
                'store_id' => ['type' => 'integer'],
                'store_name' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'autocomplete_search_analyzer',
                    'fields' => [
                        'keyword' => ['type' => 'keyword']
                    ]
                ],
                'title' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'autocomplete_search_analyzer',
                    'fields' => [
                        'keyword' => ['type' => 'keyword']
                    ]
                ],
                'slug' => ['type' => 'keyword'],
                'description' => ['type' => 'text'],
                'meta_title' => ['type' => 'text'],
                'meta_description' => ['type' => 'text'],
                'list_price' => ['type' => 'float'],
                'list_price_cents' => ['type' => 'integer'],
                'category_id' => ['type' => 'integer'],
                'category_title' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'autocomplete_search_analyzer',
                    'fields' => [
                        'keyword' => ['type' => 'keyword']
                    ]
                ],
                'stock_quantity' => ['type' => 'integer'],
                'sold_quantity' => ['type' => 'integer'],
                'images' => [
                    'type' => 'nested',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'product_id' => ['type' => 'integer'],
                        'image' => ['type' => 'keyword'],
                        'is_primary' => ['type' => 'boolean'],
                        'sort_order' => ['type' => 'integer'],
                    ]
                ],

                'variants' => [
                    'type' => 'nested',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'sku' => ['type' => 'keyword'],
                        'price' => ['type' => 'float'],
                        'price_cents' => ['type' => 'integer'],
                        'stock_quantity' => ['type' => 'integer'],
                        'images' => [
                            'type' => 'nested',
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'product_variant_id' => ['type' => 'integer'],
                                'image' => ['type' => 'keyword'],
                                'is_primary' => ['type' => 'boolean'],
                                'sort_order' => ['type' => 'integer'],
                            ]
                        ],
                        'is_popular' => ['type' => 'boolean'],
                        'attributes' => [
                            'type' => 'nested',
                            'properties' => [
                                'attribute_id' => ['type' => 'integer'],
                                'code'         => ['type' => 'keyword'],
                                'name'         => ['type' => 'text'],
                                'value'        => ['type' => 'keyword'],
                                'slug'         => ['type' => 'keyword'],
                            ]
                        ]
                    ]
                ],

                'created_at' => ['type' => 'date'],
                'updated_at' => ['type' => 'date'],
            ]
        ],
    ]
];
