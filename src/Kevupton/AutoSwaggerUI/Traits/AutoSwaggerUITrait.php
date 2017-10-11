<?php

namespace Kevupton\AutoSwaggerUI\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\File;
use Kevupton\AutoSwaggerUI\Providers\AutoSwaggerUIServiceProvider;

trait AutoSwaggerUITrait
{

    private $defaultScanDir = '/app/Http/Controllers';

    /**
     * Gets the concatenation of a string onto the base path of the application.
     *
     * @param string $path
     * @return string
     */
    private function basePath ($path = '')
    {
        return realpath(app()->basePath() . '/' . $path);
    }

    /**
     * Returns the scanned json result.
     * This is consumed by the swagger ui to create the request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJson ()
    {

        $json = app('cache')->remember(

            SWAGGER_UI_CONFIG . '.scan_output',
            sui_config('scanner.cache_duration', 0) ?: 0,
            function () {

                $paths = sui_config('scanner.paths', $this->defaultScanDir);
                $options = sui_config('scanner.options', []);

                if (is_string($paths)) {
                    $paths = explode(',', $paths);
                }

                foreach ($paths as &$dir) {
                    $dir = $this->basePath($dir);
                }

                // the scanner must be an instance of zircote/swagger-php
                $scanner = sui_config('scanner.handler', '\Kevupton\LaravelSwagger\scan');
                return $scanner($paths, $options);

            }
        );

        return response($json, 200, sui_config('scanner.headers', []));
    }

    /**
     * Gets any file based on the path requested.
     * It will attempt to find the file and return it, otherwise it will return a 404.
     *
     * @param null $path
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws FileNotFoundException
     */
    public function getUiPath ($path = null)
    {
        /*
         * Redirect all pages that are on the base to the index.html
         * This is required in order to render the appropriate styles, scripts and resources
         */
        if ($path == null || $path == '/') {
            return redirect()->route(AutoSwaggerUIServiceProvider::SWAGGER_UI_NAME, ['path' => '/index.html']);
        }

        $path = auto_swagger_path($path);

        /*
         * Try to retrieve the file and return it,
         * otherwise show a 404 if it cannot be found.
         */
        try {
            $file = File::get($path);

            // we need to replace the url in the js so we know where to get the documentation from
            if (str_contains($path, 'index.html')) {
                $file = str_replace('{{URL}}', url(sui_config('urls.json', 'http://petstore.swagger.io/v2/swagger.json')), $file);
            }
        } catch (\Exception $e) {
            throw new FileNotFoundException($path, 404, $e);
        }

        $type = File::mimeType($path);
        $ext = File::extension($path);

        // css file mime type seems to be text/plain instead of text/css
        if ($ext === 'css') $type = 'text/css';

        // make the response match the file.
        $response = response()->make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}