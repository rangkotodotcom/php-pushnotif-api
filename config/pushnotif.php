<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Mode
    |--------------------------------------------------------------------------
    |
    | By default, use development. Supported Mode: "development", "production"
    |
    */

    'pushnotif_mode' => env('PUSHNOTIF_MODE', 'development'),

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Client ID
    |--------------------------------------------------------------------------
    |
    | Client ID from PUSHNOTIF API
    |
    */

    'pushnotif_client_id' => env('PUSHNOTIF_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Client Secret
    |--------------------------------------------------------------------------
    |
    | Client Secret from PUSHNOTIF API
    |
    */

    'pushnotif_client_secret' => env('PUSHNOTIF_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Main Domain
    |--------------------------------------------------------------------------
    |
    | Main Domain from PUSHNOTIF API
    |
    */

    'pushnotif_main_domain' => env('PUSHNOTIF_MAIN_DOMAIN', 'school.sch.id'),
];
