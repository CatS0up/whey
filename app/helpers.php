<?php

declare(strict_types=1);

if (!function_exists('compare_float'))
{
    function compare_float(float $target, float $compareTo): bool
    {
        return abs($target - $compareTo) < PHP_FLOAT_EPSILON;
    }
}
