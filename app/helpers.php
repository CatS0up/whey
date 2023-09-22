<?php

declare(strict_types=1);

if ( ! function_exists('compare_float')) {
    function compare_float(float $target, float $compareTo): bool
    {
        $delta = 0.00001;

        return abs($target - $compareTo) < $delta;
    }
}
