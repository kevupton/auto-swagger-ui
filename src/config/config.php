<?php

return array(
    // whether or not the swagger ui is enabled
    'ui_enabled' => env('SWAGGER_UI_ENABLED', true),

    // the path to the swagger ui implementation. By default will use its own swagger ui
    'path' => env('SWAGGER_UI_PATH'),

    'urls' => [
        // where the swagger ui will be located
        'ui' => env('SWAGGER_UI_URL', '/api/swagger'),

        // where the json will be located
        'json' => env('SWAGGER_JSON_URL', '/api/swagger.json')
    ],

    // whether or not to enable the swagger scanner
    'scanner_enabled' => env('SWAGGER_SCAN_ENABLED', true),

    'scanner' => [
        // if you want to enable swagger scan then specify an endpoint
        'output_url' => env('SWAGGER_SCANNER_OUTPUT_URL', '/api/swagger.json'),

        // the directory to scan
        'path' => env('SWAGGER_SCANNER_PATH', '/app/Http/Controllers'),

        // the default scanner which passes the json
        'handler' => env('SWAGGER_SCANNER_HANDLER', '\Kevupton\LaravelSwagger\scan')
    ]

);