<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'key' => env('STRIPE_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'iyzico' => [
        'api_key' => env('IYZICO_API_KEY'),
        'secret_key' => env('IYZICO_SECRET_KEY'),
        'base_url' => env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'),
    ],

    'mng' => [
        'client_secret' => env('MNG_API_SECRET', '02bd71bd745a02437fc5d2fb011b62d3'),
        'client_id' => env('MNG_CLIENT_ID', '82e7844b0d5f4797adfa36fc32585a3d'),
        'endpoint' => env('MNG_API_BASE', 'https://testapi.mngkargo.com.tr/mngapi/api'),
        'test_mode' => env('MNG_TEST_MODE', true)
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
