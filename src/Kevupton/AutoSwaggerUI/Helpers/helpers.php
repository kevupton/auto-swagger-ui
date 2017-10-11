<?php

define('SWAGGER_UI_CONFIG', 'swagger-ui');

if (!function_exists('is_lumen')) {
    /**
     * Checks whether or not the application is Lumen
     *
     * @return bool
     */
    function is_lumen() {
        return class_exists('Laravel\Lumen\Application');
    }
}

if (!function_exists('is_laravel')) {
    /**
    * Checks whether or not the application is Laravel
    *
    * @return bool
    */
    function is_laravel() {
        return !is_lumen();
    }
}

if (!function_exists('auto_swagger_path')) {
    /**
     * Gets the swagger path as specified in the configuration,
     * otherwise uses the default library, included with this package.
     *
     * @param null|string $path
     * @return string
     */
    function auto_swagger_path($path = null) {
        $currentPath = realpath(__DIR__ . '/../swagger-ui/dist');
        return realpath(config('.swaggerpath', $currentPath) . '/' . $path);
    }
}

if (!function_exists('sui_config')) {
    function sui_config ($key, $default = null) {

        if (is_null($key)) {
            return config(SWAGGER_UI_CONFIG);
        }

        if (is_array($key)) {
            foreach ($key as &$_key => $value) {
                $_key = SWAGGER_UI_CONFIG . '.' . $_key;
            }
            return config($key);
        }

        return sui_config('' . $key, $default);
    }
}