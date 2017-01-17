<?php

// mix('css/app.css')
// mix('js/app.js')
function mix($path, $manifest = false, $shouldHotReload = false)
{
    if (! $manifest) static $manifest;
    if (! $shouldHotReload) static $shouldHotReload;

    if (! $manifest) {
        $manifestPath = public_path('mix-manifest.json');
        $shouldHotReload = file_exists(public_path('hot'));

        if (! file_exists($manifestPath)) {
            throw new Exception(
                'The Laravel Mix manifest file does not exist. ' .
                'Please run "npm run webpack" and try again.'
            );
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
    }

    if (! starts_with($path, '/')) $path = "/{$path}";

    if (! array_key_exists($path, $manifest)) {
        throw new Exception(
            "Unknown Laravel Mix file path: {$path}. Please check your requested " .
            "webpack.mix.js output path, and try again."
        );
    }

    return $shouldHotReload
        ? "http://localhost:8080{$manifest[$path]}"
        : url($manifest[$path]);
}
