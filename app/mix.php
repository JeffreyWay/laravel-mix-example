<?php

// mix('css/app.css')
// mix('js/app.js')
function mix($path, $json = false, $shouldHotReload = false)
{
    if (! $json) static $json;
    if (! $shouldHotReload) static $shouldHotReload;

    if (! $json) {
        $manifestPath = public_path('manifest.json');
        $shouldHotReload = file_exists(public_path('hot'));

        if (! file_exists($manifestPath)) {
            throw new Exception(
                'The Laravel Mix manifest file does not exist. ' .
                'Please run "npm run webpack" and try again.'
            );
        }

        $json = json_decode(file_get_contents($manifestPath), true);
    }

    $path = pathinfo($path, PATHINFO_BASENAME);

    if (! array_key_exists($path, $json)) {
        throw new Exception(
            'Unknown file path. Please check your requested ' .
            'webpack.mix.js output path, and try again.'
        );
    }

    return $shouldHotReload
        ? "http://localhost:8080{$json[$path]}"
        : url($json[$path]);
}
