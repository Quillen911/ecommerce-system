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
                'category' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'text'],
                        'slug' => ['type' => 'keyword'],
                        'gender_id' => ['type' => 'integer'],
                        'parent_id' => ['type' => 'integer'],
                        'gender' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'title' => ['type' => 'text'],
                                'slug' => ['type' => 'keyword'],
                            ]
                        ],
                        'parent' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'title' => ['type' => 'text'],
                                'slug' => ['type' => 'keyword'],
                            ]
                        ]
                    ]
                ],
                'category_title' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'autocomplete_search_analyzer',
                    'fields' => [
                        'keyword' => ['type' => 'keyword']
                    ]
                ],
                'variants' => [
                    'type' => 'nested',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'product_id' => ['type' => 'integer'],
                        'sku' => ['type' => 'keyword'],
                        'slug' => ['type' => 'keyword'],
                        'color_name' => ['type' => 'keyword'],
                        'color_code' => ['type' => 'keyword'],
                        'price_cents' => ['type' => 'integer'],
                        'is_popular' => ['type' => 'boolean'],
                        'is_active' => ['type' => 'boolean'],
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
                        'sizes' => [
                            'type' => 'nested',
                            'properties' => [
                                'product_variant_id' => ['type' => 'integer'],
                                'size_option_id' => ['type' => 'integer'],
                                'size_option' => [
                                    'type' => 'nested',
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'attribute_id' => ['type' => 'integer'],
                                        'value' => ['type' => 'text'],
                                        'slug' => ['type' => 'keyword'],
                                    ]
                                ],
                                'sku' => ['type' => 'keyword'],
                                'price_cents' => ['type' => 'integer'],
                                'is_active' => ['type' => 'boolean'],
                                'inventory' => [
                                    'type' => 'nested',
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'variant_size_id' => ['type' => 'integer'],
                                        'warehouse_id' => ['type' => 'integer'],
                                        'on_hand' => ['type' => 'integer'],
                                        'reserved' => ['type' => 'integer'],
                                        'available' => ['type' => 'integer'],
                                        'min_stock_level' => ['type' => 'integer'],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'gender' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'autocomplete_search_analyzer',
                    'fields' => [
                        'keyword' => ['type' => 'keyword']
                    ]
                ],

                'created_at' => ['type' => 'date'],
                'updated_at' => ['type' => 'date'],
            ]
        ],
    ]
];
