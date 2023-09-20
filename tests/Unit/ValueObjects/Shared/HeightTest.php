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
            'centimeters to foots' => [
                HeightUnit::Centimeters,
                HeightUnit::Foots,
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
            'meters to foots' => [
                HeightUnit::Meters,
                HeightUnit::Foots,
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
            'inches to foots' => [
                HeightUnit::Inches,
                HeightUnit::Foots,
                0.0833333333,
            ],
            // Inches - end

            // Foots - start
            'foots to centimeters' => [
                HeightUnit::Foots,
                HeightUnit::Centimeters,
                30.48,
            ],
            'foots to meters' => [
                HeightUnit::Foots,
                HeightUnit::Meters,
                0.3048,
            ],
            'foots to inches' => [
                HeightUnit::Foots,
                HeightUnit::Inches,
                12,
            ],
            'foots to foots' => [
                HeightUnit::Foots,
                HeightUnit::Foots,
                1.0,
            ],
            // Foots - end
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
            'foots and inches format when height unit are inches' => [
                HeightUnit::Inches,
                [
                    ['value' => 0.1234, 'string' => '0ft 0.123in',],
                    ['value' => 1234, 'string' => '102ft 10in',],
                    ['value' => 12.34, 'string' => '1ft 0.34in',],
                ],
            ],
            // Inches - end

            // Foots - start
            'foots and inches format when height unit are foots' => [
                HeightUnit::Foots,
                [
                    ['value' => 0.1234, 'string' => '0ft 1.481in',],
                    ['value' => 1234, 'string' => '1234ft 0in',],
                    ['value' => 12.24, 'string' => '12ft 2.88in',],
                ],
            ],
            // Foots - end
        ];
    }
}
