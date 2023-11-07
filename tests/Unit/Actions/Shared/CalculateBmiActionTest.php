<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Shared;

use App\Actions\Shared\CalculateBmiAction;
use App\Enums\HeightUnit;
use App\Enums\WeightUnit;
use App\ValueObjects\Shared\Height;
use App\ValueObjects\Shared\Weight;
use PHPUnit\Framework\TestCase;

class CalculateBmiActionTest extends TestCase
{
    private CalculateBmiAction $actionUnderTest;

    protected function setUp(): void
    {
        $this->actionUnderTest = new CalculateBmiAction();
    }


    /**
     * @dataProvider bmiForDifferentWeightAndHeightUnitsProvider
     *
     * @test
     * */
    public function it_should_calculate_bmi_for_different_height_and_weight_units(
        HeightUnit $heightUnit,
        WeightUnit $weightUnit,
        float $heightValue,
        float $weightValue,
        float $expectedBmi,
    ): void {
        $height = Height::fromValueAndUnit($heightValue, $heightUnit);
        $weight = Weight::fromValueAndUnit($weightValue, $weightUnit);

        $actual = $this->actionUnderTest->execute($weight, $height);

        $this->assertEquals($expectedBmi, $actual);
    }

    public static function bmiForDifferentWeightAndHeightUnitsProvider(): array
    {
        return [
            'Centimeters and Grams' => [
                HeightUnit::Centimeters,
                WeightUnit::Grams,
                170,
                70000,
                24.22
            ],

            'Centimeters and Kilograms' => [
                HeightUnit::Centimeters,
                WeightUnit::Kilograms,
                180,
                70,
                21.6
            ],

            'Centimeters and Ounces' => [
                HeightUnit::Centimeters,
                WeightUnit::Ounces,
                155,
                176,
                2.08
            ],

            'Centimeters and Pounds' => [
                HeightUnit::Centimeters,
                WeightUnit::Pounds,
                155,
                330,
                62.3
            ],

            'Meters and Grams' => [
                HeightUnit::Meters,
                WeightUnit::Grams,
                1.70,
                70000,
                24.22
            ],

            'Meters and Kilograms' => [
                HeightUnit::Meters,
                WeightUnit::Kilograms,
                1.80,
                70,
                21.6
            ],

            'Meters and Ounces' => [
                HeightUnit::Meters,
                WeightUnit::Ounces,
                1.55,
                176,
                2.08
            ],

            'Meters and Pounds' => [
                HeightUnit::Meters,
                WeightUnit::Pounds,
                1.55,
                330,
                62.3
            ],

            'Inches and Grams' => [
                HeightUnit::Inches,
                WeightUnit::Grams,
                66,
                1543.24,
                0.55
            ],

            'Inches and Kilograms' => [
                HeightUnit::Inches,
                WeightUnit::Kilograms,
                70,
                70,
                22.14
            ],

            'Inches and Ounces' => [
                HeightUnit::Inches,
                WeightUnit::Ounces,
                61,
                176,
                2.08
            ],

            'Inches and Pounds' => [
                HeightUnit::Inches,
                WeightUnit::Pounds,
                61,
                330,
                62.35
            ],

            'Feet and Grams' => [
                HeightUnit::Feet,
                WeightUnit::Grams,
                5.5,
                15432.37,
                5.49
            ],

            'Feet and Kilograms' => [
                HeightUnit::Feet,
                WeightUnit::Kilograms,
                6,
                70,
                20.93
            ],

            'Feet and Ounces' => [
                HeightUnit::Feet,
                WeightUnit::Ounces,
                5,
                176,
                2.15
            ],

            'Feet and Pounds' => [
                HeightUnit::Feet,
                WeightUnit::Pounds,
                5,
                330,
                64.45
            ]
        ];
    }
}
