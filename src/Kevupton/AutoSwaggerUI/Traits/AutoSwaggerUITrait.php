<?php

namespace Kevupton\AutoSwaggerUI\Traits;

use Illuminate\Support\Facades\File;

trait AutoSwaggerUITrait {

    private $defaultScanDir = '/app/Http/Controllers';

    /**
     * Gets the concatenation of a string onto the base path of the application.
     *
     * @param string $path
     * @return string
     */
    private function basePath($path = '') {
        return realpath(app()->basePath() . '/' . $path);
    }

    /**
     * Returns the scanned json result.
     * This is consumed by the swagger ui to create the request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJson() {
        // the directory to scan
        $location = $this->basePath(config('swagger.scan.directory', $this->defaultScanDir));
        // the scanner must be an instance of zircote/swagger-php
        $scanner = config('swagger.scan.scanner', '\Kevupton\LaravelSwagger\scan');

        return response()->json($scanner($location));
    }

    /**
     * Gets any file based on the path requested.
     * It will attempt to find the file and return it, otherwise it will return a 404.
     *
     * @param null $path
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getUiPath($path = null) {
        /*
         * Redirect all pages that are on the base to the index.html
         * This is required in order to render the appropriate styles, scripts and resources
         */
        if ($path == null || $path == '/') {
            return redirect()->route('auto-swagger-ui', ['path' => '/index.html']);
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
                $file = str_replace('{{URL}}', config('swagger.urls.json', 'http://petstore.swagger.io/v2/swagger.json'), $file);
            }
        }
        catch (\Exception $e) {
            return response('',404);
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