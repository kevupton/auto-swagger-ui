# auto-swagger-ui

## Install
```bash
composer require kevupton/auto-swagger-php
```

## Setup

### Add to the Service Providers:
#### Laravel:
In `config/app.php` add `\Kevupton\AutoSwaggerUI\Providers\AutoSwaggerUIServiceProvider::class,` to `providers`.

#### Lumen:
In `bootstrap/app.php` add `$app->register(\Kevupton\AutoSwaggerUI\Providers\AutoSwaggerUIServiceProvider::class);`


## Config

public the configuration file:

```bash
php artisan vendor:publish
```
Then the config pieces can be edited how you please.
```php
<?php

return array(
    // All values except are set by default

    // the path to the swagger ui implementation. By default will use its own swagger ui
//    'path' => app_path('/swagger/dist'),

    'urls' => [
        // the url of the swagger ui
        'ui' => '/swagger-ui',
        // the url location for the scanned output (point it to your scan endpoint
        'json' => '/swagger.json'
    ],

    // for scanning and generating swagger json
    'scan' => [
        // if you want to enable swagger scan then specify an endpoint
        'endpoint' => '/swagger.json',
        // the directory to scan
        'directory' => '/app/Http/Controllers',
        // the default scanner which passes the json
//        'scanner' => '\Kevupton\LaravelSwagger\scan'
    ]

);
```

If you do not wish to have the scanning functionality, simply comment out the scanner. By default it will already 
be commented out.

*Note: Lumen will probably need to copy the config from the config file in this package, to `swagger.php` and 
register the configuration `$app->configure('swagger');` in the `bootstrap/app.js`.*