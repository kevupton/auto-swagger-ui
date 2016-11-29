<?php namespace Kevupton\AutoSwaggerUI\Providers;

use Illuminate\Support\ServiceProvider;
use Kevupton\AutoSwaggerUI\AutoSwaggerUI;
use Kevupton\AutoSwaggerUI\Controllers\LaravelController;
use Kevupton\AutoSwaggerUI\Controllers\LumenController;

/**
 * Class AutoSwaggerUIServiceProvider
 *
 * Service provider for registering the swagger ui configuration.
 *
 * @package Kevupton\Referrals\Providers
 */
class AutoSwaggerUIServiceProvider extends ServiceProvider {

    const LUMEN_CONTROLLER = LumenController::class;
    const LARAVEL_CONTROLLER = LaravelController::class;

    const SWAGGER_UI_NAME = 'auto-swagger-ui';
    const SWAGGER_JSON_NAME = 'auto-swagger-ui.json';

    const DEFAULT_UI_URL = '/swagger-ui';
    const DEFAULT_JSON_URL = '/swagger.json';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../../config/config.php' => config_path('swagger-config.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $uiUrl      = config('swagger-json.ui-url', self::DEFAULT_UI_URL);
        $jsonUrl    = config('swagger-json.json-url',self::DEFAULT_JSON_URL);

        $uiFn = '@getUiPath';
        $jsonFn = '@getJson';

        $isLaravel = is_laravel();

        // registering a route varies for laravel and lumen
        if ($isLaravel) {
            \Route::get($uiUrl . '{path}',  ['as' => self::SWAGGER_UI_NAME,     'uses' => self::LARAVEL_CONTROLLER . $uiFn])->where(['path' => '.*']);
            \Route::get($jsonUrl,           ['as' => self::SWAGGER_JSON_NAME,   'uses' => self::LARAVEL_CONTROLLER . $jsonFn]);
        }
        else {
            $this->app->get($uiUrl . '{path:.*}',   ['as' => self::SWAGGER_UI_NAME,     'uses' => self::LUMEN_CONTROLLER . $uiFn]);
            $this->app->get($jsonUrl,               ['as' => self::SWAGGER_JSON_NAME,   'uses' => self::LUMEN_CONTROLLER . $jsonFn]);
        }
    }
}