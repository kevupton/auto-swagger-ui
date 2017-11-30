# auto-swagger-ui
Give your project some swagger docs in a matter of seconds. Simply just install the package and service providers,
optionally adding the configuration file, then BAM you now have a swagger ui endpoint. 

Also integrates with swagger php annotations, allowing you scan your annotations and generate a json object at 
a specified endpoint. 1, 2, 3, too much swag. ;)

## Install
```bash
composer require kevupton/auto-swagger-ui
```

## Setup

### Add to the Service Providers:
#### Laravel:
In `config/app.php` , `providers` add
```php
\Kevupton\AutoSwaggerUI\Providers\AutoSwaggerUIServiceProvider::class,
```

#### Lumen:
In `bootstrap/app.php` add
```php
$app->register(\Kevupton\AutoSwaggerUI\Providers\AutoSwaggerUIServiceProvider::class);
```


## Run
Once you have registered the service provider, you will be able to access the swagger page at:
```bash
http://{my-host}/api/swagger
```
Or the json at
```bash
http://{my-host}/api/swagger.json
```

## Config

The package can be configured by publishing the config or copying the config from the vendor files.
To publish: 
```bash
php artisan vendor:publish
```

The config:
```php
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
        'paths' => env('SWAGGER_SCANNER_PATH', '/app/Http/Controllers'),

        // the default scanner which passes the json
        'handler' => env('SWAGGER_SCANNER_HANDLER', '\Kevupton\LaravelSwagger\scan'),

        // the options that is passed into the scanner
        'options' => [

            // the models to include in the scan
            'models' => []
        ],

        // how long to save the scanned json for
        'cache_duration' => env('SWAGGER_SCANNER_CACHE_DURATION', null),

        // headers in the JSON response (for CORS)
        'headers' => [
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization'
        ]
    ]

);
```

*Note: Lumen will probably need to copy the config from the config file in this package, to `swagger.php` and 
register the configuration `$app->configure('swagger');` in the `bootstrap/app.js`.*

**NGINX**
You may need to adjust your config file so that redirects for files not found using this line:

`try_files $uri /index.php;`

Example:

```conf
    location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc|svg|woff|woff2|ttf)$ {
      try_files $uri /index.php;
      expires 1M;
      access_log off;
      add_header Cache-Control "public";
    }

    location ~* \.(?:css|js)$ {
      try_files $uri /index.php;
      expires 7d;
      access_log off;
      add_header Cache-Control "public";
    }
```
