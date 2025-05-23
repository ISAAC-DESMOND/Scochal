<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*','broadcasting/auth', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],
    'allowed_origins'   => ['http://16.16.101.178'], // your exact origin
    'allowed_origins_patterns' => [],
    'allowed_headers'   => ['*'],   // allow X-CSRF-TOKEN, Authorization
    'exposed_headers'   => [],
    'max_age'           => 0,
    'supports_credentials' => true, // allow cookies to be sent

];
