<?php

declare(strict_types=1);

namespace App\Actions\Shared;

use App\Enums\HeightUnit;
use App\Enums\WeightUnit;
use App\ValueObjects\Shared\Height;
use App\ValueObjects\Shared\Weight;

class CalculateBmiAction
{
    /** @var int */
    private const SQUARE_POWER = 2;
    /** @var int */
    private const BMI_RESULT_PRECISION = 1;

    public function execute(Weight $weight, Height $height): float
    {
        $kilograms = $weight->toOtherUnit(WeightUnit::Kilograms);
        $meters = $height->toOtherUnit(HeightUnit::Meters);
        $result = $kilograms->value / ($meters->value ** self::SQUARE_POWER);

        return round($result, self::BMI_RESULT_PRECISION);
    }
}
