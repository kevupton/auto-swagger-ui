<?php namespace Kevupton\AutoSwaggerUI\Providers;

use Illuminate\Support\ServiceProvider;
use Kevupton\AutoSwaggerUI\Controllers\LaravelController;
use Kevupton\AutoSwaggerUI\Controllers\LumenController;
use Laravel\Lumen\Application;

/**
 * Class AutoSwaggerUIServiceProvider
 *
 * Service provider for registering the swagger ui configuration.
 *
 * @package Kevupton\Referrals\Providers
 */
class AutoSwaggerUIServiceProvider extends ServiceProvider
{
    const LUMEN_CONTROLLER = LumenController::class;
    const LARAVEL_CONTROLLER = LaravelController::class;

    const SWAGGER_UI_NAME = 'auto-swagger-ui';
    const SWAGGER_JSON_NAME = 'auto-swagger-ui.json';

    const DEFAULT_UI_URL = '/swagger-ui';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot ()
    {
        $this->publishes([__DIR__ . '/../../../config/config.php' => config_path(SWAGGER_UI_CONFIG . '.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register ()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/config.php', SWAGGER_UI_CONFIG
        );

        $this->loadRoutes();
    }

    /**
     * Loads all the routes
     */
    private function loadRoutes ()
    {
        $uiUrl = sui_config('urls.ui', self::DEFAULT_UI_URL);
        $scannerOutputUrl = sui_config('scanner.output_url');
        $scannerEnabled = sui_config('scanner_enabled');
        $uiEnabled = sui_config('ui_enabled');

        $uiFn = '@getUiPath';
        $jsonFn = '@getJson';

        // registering a route varies for laravel and lumen
        if (is_laravel()) {
            // ---> Laravel Routes <----
            if ($scannerEnabled) {
                \Route::get($scannerOutputUrl, ['as' => self::SWAGGER_JSON_NAME, 'uses' => self::LARAVEL_CONTROLLER . $jsonFn]);
            }

            if ($uiEnabled) {
                \Route::get($uiUrl . '{path}', ['as' => self::SWAGGER_UI_NAME, 'uses' => self::LARAVEL_CONTROLLER . $uiFn])->where(['path' => '.*']);
            }
        } else {
            // ---> Lumen Routes <---
            /** @var Application $app */
            $app = $this->app;
            $router = $app->router;

            if ($scannerEnabled) {
                $router->get($scannerOutputUrl, ['as' => self::SWAGGER_JSON_NAME, 'uses' => self::LUMEN_CONTROLLER . $jsonFn]);
            }

            if ($uiEnabled) {
                $router->get($uiUrl . '{path:.*}', ['as' => self::SWAGGER_UI_NAME, 'uses' => self::LUMEN_CONTROLLER . $uiFn]);
            }
        }
    }
}