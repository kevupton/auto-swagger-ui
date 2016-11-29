<?php

return array(
    // All values except are set by default

    // the path to the swagger ui implementation. By default will use its own swagger ui
//    'path' => app_path('/swagger/dist'),

    'urls' => [
        // the url of the swagger ui
        'ui' => '/swagger-ui',
        // the url location for the scanned output (point it to your scan endpoint
        'json' => 'http://petstore.swagger.io/v2/swagger.json'
    ],

    // for scanning and generating swagger json
//    'scan' => [
//        // if you want to enable swagger scan then specify an endpoint
//        'endpoint' => '/swagger.json',
//        // the directory to scan
//        'directory' => '/app/Http/Controllers',
//        // the default scanner which passes the json
////        'scanner' => '\Kevupton\LaravelSwagger\scan'
//    ]

);