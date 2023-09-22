<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects\Shared;

use App\Enums\HeightUnit;
use App\ValueObjects\Shared\Height;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class HeightTest extends TestCase
{
    /** @test */
    public function it_should_throw_InvalidArgumentException_when_given_value_is_less_than_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value should be greater or equals to 0');

        Height::fromValueAndUnit(-1.0, HeightUnit::Meters);
    }

    /** @test */
    public function it_should_create_height_instance_by_correct_value_and_unit(): void
    {
        $height = Height::fromValueAndUnit(1.0, HeightUnit::Meters);

        $this->assertInstanceOf(Height::class, $height);
        $this->assertEquals(1.0, $height->value);
        $this->assertEquals(HeightUnit::Meters, $height->unit);
    }

    /**
     * @test
     * @dataProvider convertedValuesProvider
     * */
    public function it_should_convert(HeightUnit $base, HeightUnit $convertTo, float $expectedValue): void
    {
        $height = Height::fromValueAndUnit(1.0, $base);
        $converted = $height->toOtherUnit($convertTo);

        $this->assertEquals($expectedValue, $converted->value);
        $this->assertEquals($convertTo, $converted->unit);
    }

    /**
     * @test
     * @dataProvider formattedStringsProvider
     * */
    public function it_should_display_correct_format_when_given_instance_is_converting_to_string(HeightUnit $unit, array $cases): void
    {
        foreach ($cases as $case) {
            $height = Height::fromValueAndUnit($case['value'], $unit);

            $actual = (string) $height;

            $this->assertSame($case['string'], $actual);
        }
    }

    /**
     * @test
     * @dataProvider comparisionTrueResultsProvider
     * */
    public function it_should_compare_height_instances_with_other_units_when_given_instances_are_equals(Height $subject, Height $comparision): void
    {
        $this->assertTrue($subject->equalsTo($comparision));
    }

    /**
     * @test
     * @dataProvider comparisionFalseResultsProvider
     * */
    public function it_should_compare_height_instances_with_other_units_when_given_instances_are_not_equals(Height $subject, Height $comparision): void
    {
        $this->assertTrue($subject->notEqualsTo($comparision));
    }

    public static function convertedValuesProvider(): array
    {
        return [
            // Centimeters - start
            'centimeters to centimeters' => [
                HeightUnit::Centimeters,
                HeightUnit::Centimeters,
                1.0,
            ],
            'centimeters to meters' => [
                HeightUnit::Centimeters,
                HeightUnit::Meters,
                0.01,
            ],
            'centimeters to inches' => [
                HeightUnit::Centimeters,
                HeightUnit::Inches,
                0.393700787,
            ],
            'centimeters to feet' => [
                HeightUnit::Centimeters,
                HeightUnit::Feet,
                0.032808399,
            ],
            // Centimeters - end

            // Meters - start
            'meters to centimeters' => [
                HeightUnit::Meters,
                HeightUnit::Centimeters,
                100,
            ],
            'meters to meters' => [
                HeightUnit::Meters,
                HeightUnit::Meters,
                1.0,
            ],
            'meters to inches' => [
                HeightUnit::Meters,
                HeightUnit::Inches,
                39.3700787,
            ],
            'meters to feet' => [
                HeightUnit::Meters,
                HeightUnit::Feet,
                3.2808399,
            ],
            // Meters - end

            // Inches - start
            'inches to centimeters' => [
                HeightUnit::Inches,
                HeightUnit::Centimeters,
                2.54,
            ],
            'inches to meters' => [
                HeightUnit::Inches,
                HeightUnit::Meters,
                0.0254,
            ],
            'inches to inches' => [
                HeightUnit::Inches,
                HeightUnit::Inches,
                1.0,
            ],
            'inches to feet' => [
                HeightUnit::Inches,
                HeightUnit::Feet,
                0.0833333333,
            ],
            // Inches - end

            // Feet - start
            'feet to centimeters' => [
                HeightUnit::Feet,
                HeightUnit::Centimeters,
                30.48,
            ],
            'feet to meters' => [
                HeightUnit::Feet,
                HeightUnit::Meters,
                0.3048,
            ],
            'feet to inches' => [
                HeightUnit::Feet,
                HeightUnit::Inches,
                12,
            ],
            'feet to feet' => [
                HeightUnit::Feet,
                HeightUnit::Feet,
                1.0,
            ],
            // Feet - end
        ];
    }

    public static function formattedStringsProvider(): array
    {
        return [
            // Centimeters - start
            'meters and centimeters format when height unit are centimeters' => [
                HeightUnit::Centimeters,
                [
                    ['value' => 0.125, 'string' => '0m 0.125cm',],
                    ['value' => 1250, 'string' => '12m 50cm',],
                    ['value' => 12.50, 'string' => '0m 12.5cm',],
                ],
            ],
            // Centimeters - end

            // Meters - start
            'meters and centimeters format when height unit are meters' => [
                HeightUnit::Meters,
                [
                    ['value' => 0.5, 'string' => '0m 50cm',],
                    ['value' => 1250, 'string' => '1250m 0cm',],
                    ['value' => 12.50, 'string' => '12m 50cm',],
                ],
            ],
            // Meters - end

            // Inches - start
            'feet and inches format when height unit are inches' => [
                HeightUnit::Inches,
                [
                    ['value' => 0.1234, 'string' => '0ft 0.123in',],
                    ['value' => 1234, 'string' => '102ft 10in',],
                    ['value' => 12.34, 'string' => '1ft 0.34in',],
                ],
            ],
            // Inches - end

            // Feet - start
            'feet and inches format when height unit are feet' => [
                HeightUnit::Feet,
                [
                    ['value' => 0.1234, 'string' => '0ft 1.481in',],
                    ['value' => 1234, 'string' => '1234ft 0in',],
                    ['value' => 12.24, 'string' => '12ft 2.88in',],
                ],
            ],
            // Feet - end
        ];
    }

    public static function comparisionTrueResultsProvider(): array
    {
        return [
            // Centimeters - start
            'centimeters to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
            ],
            'centimeters to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(0.01, HeightUnit::Meters),
            ],
            'centimeters to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(0.393700787, HeightUnit::Inches),
            ],
            'centimeters to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(0.032808399, HeightUnit::Feet),
            ],
            // Centimeters - end

            // Meters - start
            'meters to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(100, HeightUnit::Centimeters),
            ],
            'meters to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
            ],
            'meters to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(39.3700787, HeightUnit::Inches),
            ],
            'meters to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(3.2808399, HeightUnit::Feet),
            ],
            // Meters - end

            // Inches - start
            'inches to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(2.54, HeightUnit::Centimeters),
            ],
            'inches to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(0.0254, HeightUnit::Meters),
            ],
            'inches to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
            ],
            'inches to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(0.0833333333, HeightUnit::Feet),
            ],
            // Inches - end

            // Feet - start
            'feet to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(30.48, HeightUnit::Centimeters),
            ],
            'feet to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(0.3048, HeightUnit::Meters),
            ],
            'feet to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(12, HeightUnit::Inches),
            ],
            'feet to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
            ],
            // Feet - end
        ];
    }

    public static function comparisionFalseResultsProvider(): array
    {
        return [
            // Centimeters - start
            'centimeters to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(10.0, HeightUnit::Centimeters),
            ],
            'centimeters to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(0.0001, HeightUnit::Meters),
            ],
            'centimeters to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(0.1, HeightUnit::Inches),
            ],
            'centimeters to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Centimeters),
                Height::fromValueAndUnit(0.2, HeightUnit::Feet),
            ],
            // Centimeters - end

            // Meters - start
            'meters to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(10000, HeightUnit::Centimeters),
            ],
            'meters to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(100.0, HeightUnit::Meters),
            ],
            'meters to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(39, HeightUnit::Inches),
            ],
            'meters to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Meters),
                Height::fromValueAndUnit(3, HeightUnit::Feet),
            ],
            // Meters - end

            // Inches - start
            'inches to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(2, HeightUnit::Centimeters),
            ],
            'inches to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(1.0254, HeightUnit::Meters),
            ],
            'inches to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(2.0, HeightUnit::Inches),
            ],
            'inches to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Inches),
                Height::fromValueAndUnit(4, HeightUnit::Feet),
            ],
            // Inches - end

            // Feet - start
            'feet to centimeters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(350.48, HeightUnit::Centimeters),
            ],
            'feet to meters' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(6.3048, HeightUnit::Meters),
            ],
            'feet to inches' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(169, HeightUnit::Inches),
            ],
            'feet to feet' => [
                Height::fromValueAndUnit(1.0, HeightUnit::Feet),
                Height::fromValueAndUnit(2.0, HeightUnit::Feet),
            ],
            // Feet - end
        ];
    }
}
