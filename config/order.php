<?php

return[
    'cargo' => [
        'threshold' => env('ORDER_CARGO_THRESHOLD', 200),
        'price' => env('ORDER_CARGO_PRICE', 50),
    ],

    'currency' => env('ORDER_CURRENCY', 'TRY'),

    'default' => [
        'payment_status' => 'failed',
        'order_status' =>'Başarısız Ödeme',
    ],

    'payment' => [
        'timeout' => 30, //sec
        'retry_attempts' => 3,
    ],

    'inventory' => [
        'check_stock' => true,
        'reserve_stock' => false,
    ],
];