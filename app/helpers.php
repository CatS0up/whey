<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

if ( ! function_exists('compare_float')) {
    function compare_float(float $target, float $compareTo): bool
    {
        $delta = 0.00001;

        return abs($target - $compareTo) < $delta;
    }

    if ( ! function_exists('file_full_path')) {
        function file_full_path(string $disk, string $path): string
        {
            return Storage::disk($disk)->path($path);
        }
    }

    if ( ! function_exists('file_url')) {
        function file_url(string $disk, string $path): string
        {
            return Storage::disk($disk)->url($path);
        }
    }

    if ( ! function_exists('route_is')) {
        function route_is(string ...$patterns): bool
        {
            return Route::is(...$patterns);
        }
    }
}
