<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Digipay API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'production' => [
            'base_url' => 'https://api.mydigipay.com',
            'web_url'  => 'https://app.mydigipay.ir',
        ],
        'sandbox' => [
            'base_url' => 'https://uat.mydigipay.info',
            'web_url'  => 'https://uatweb.mydigipay.info',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Paths
    |--------------------------------------------------------------------------
    */
    'paths' => [
        'oauth_token'   => '/digipay/api/oauth/token',
        'ticket'        => '/digipay/api/tickets/business',
        'verify'        => '/digipay/api/purchases/verify',
        'reverse'       => '/digipay/api/reverse',
        'refund'        => '/digipay/api/refunds',
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Configuration
    |--------------------------------------------------------------------------
    */
    'timeout'      => env('DIGIPAY_TIMEOUT', 30),
    'retry_times'  => env('DIGIPAY_RETRY_TIMES', 2),
    'retry_delay'  => env('DIGIPAY_RETRY_DELAY', 500),

    /*
    |--------------------------------------------------------------------------
    | Default API Version
    |--------------------------------------------------------------------------
    */
    'default_api_version' => '2022-02-02',

    /*
    |--------------------------------------------------------------------------
    | Gateway Types (for preferredGateway)
    |--------------------------------------------------------------------------
    */
    'gateway_types' => [
        'wallet' => 0,
        'ipg'    => 2,
        'credit' => 5,
        'bnpl'   => 13,
    ],

    /*
    |--------------------------------------------------------------------------
    | Ticket Type for UPG
    |--------------------------------------------------------------------------
    */
    'ticket_type' => 11,

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled'          => env('DIGIPAY_LOGGING', true),
        'channel'          => env('DIGIPAY_LOG_CHANNEL', 'stack'),
        'sensitive_fields' => ['client_secret', 'password', 'access_token', 'refresh_token'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Cache Configuration
    |--------------------------------------------------------------------------
    */
    'token_cache' => [
        'key'          => 'digipay_access_token',
        'buffer'       => 300, // Refresh token 5 minutes before expiry
    ],
];
