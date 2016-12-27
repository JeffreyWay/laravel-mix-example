<?php

/**
 * Get the path to the appropriate Laravel Mix assets.
 *
 * @param  string  $file
 * @return string
 *
 * @throws Exception
 */
function mix($file) {
    static $manifest;
    static $shouldHotReload;

    if (! $manifest) {
        $manifestPath = storage_path('framework/cache/Mix.json');
        $shouldHotReload = file_exists(storage_path('framework/cache/hot'));

        if (! file_exists($manifestPath)) {
            throw new Exception(
                'The Laravel Mix manifest file does not exist. ' . 
                'Please run "npm run webpack" and try again.'
            );
        }
        
        $manifest = json_decode(file_get_contents($manifestPath), true);
    }

    $url = collect($manifest['assetsByChunkName'])
        ->flatten()
        ->first(function ($compiledFile) use ($file, $manifest) {
            $compiledFile = trim(str_replace($manifest['hash'].'.', '', $compiledFile), '/');

            return $file == $compiledFile;
        });

    return $shouldHotReload ? "http://localhost:8080{$url}" : url($url);
}
