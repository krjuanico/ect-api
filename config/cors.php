<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS)
    |--------------------------------------------------------------------------
    | The Vue UI runs on a different address (e.g. http://localhost:5173) than the
    | API (http://localhost:8000), so the browser asks the API for permission first.
    |
    | The default list below covers local development. In production, set the real
    | UI address in .env, comma separated:
    |   CORS_ALLOWED_ORIGINS=https://drmd.example.gov.ph
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env(
            'CORS_ALLOWED_ORIGINS',
            'http://localhost:5173,http://localhost:5174,http://localhost:5175,'
            .'http://127.0.0.1:5173,http://127.0.0.1:5174,http://127.0.0.1:5175'
        ))
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // The UI sends a Bearer token, not cookies, so credentials are not needed.
    'supports_credentials' => false,

];