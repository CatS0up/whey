<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects\Shared;

use App\Enums\WeightUnit;
use App\ValueObjects\Shared\Weight;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    /** @test */
    public function it_should_throw_InvalidArgumentException_when_given_value_is_less_than_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value should be greater or equals to 0');

        Weight::fromValueAndUnit(-1.0, WeightUnit::Kilograms);
    }

    /** @test */
    public function it_should_create_weight_instance_by_correct_value_and_unit(): void
    {
        $weight = Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms);

        $this->assertInstanceOf(Weight::class, $weight);
        $this->assertEquals(1.0, $weight->value);
        $this->assertEquals(WeightUnit::Kilograms, $weight->unit);
    }

    /**
     * @test
     * @dataProvider convertedValuesProvider
     * */
    public function it_should_convert(WeightUnit $base, WeightUnit $convertTo, float $expectedValue): void
    {
        $weight = Weight::fromValueAndUnit(1.0, $base);
        $converted = $weight->toOtherUnit($convertTo);

        $this->assertEquals($expectedValue, $converted->value);
        $this->assertEquals($convertTo, $converted->unit);
    }

    /**
     * @test
     * @dataProvider formattedStringsProvider
     * */
    public function it_should_display_correct_format_when_given_instance_is_converting_to_string(WeightUnit $unit, array $cases): void
    {
        foreach ($cases as $case) {
            $weight = Weight::fromValueAndUnit($case['value'], $unit);

            $actual = (string) $weight;

            $this->assertSame($case['string'], $actual);
        }
    }

    /**
     * @test
     * @dataProvider comparisionTrueResultsProvider
     * */
    public function it_should_compare_weight_instances_with_other_units_when_given_instances_are_equals(Weight $subject, Weight $comparision): void
    {
        $this->assertTrue($subject->equalsTo($comparision));
    }

    /**
     * @test
     * @dataProvider comparisionFalseResultsProvider
     * */
    public function it_should_compare_weight_instances_with_other_units_when_given_instances_are_not_equals(Weight $subject, Weight $comparision): void
    {
        $this->assertTrue($subject->notEqualsTo($comparision));
    }

    public static function convertedValuesProvider(): array
    {
        return [
            // Grams - start
            'grams to grams' => [
                WeightUnit::Grams,
                WeightUnit::Grams,
                1.0,
            ],
            'grams to kilograms' => [
                WeightUnit::Grams,
                WeightUnit::Kilograms,
                0.001,
            ],
            'grams to ounces' => [
                WeightUnit::Grams,
                WeightUnit::Ounces,
                0.0352739619,
            ],
            'grams to pounds' => [
                WeightUnit::Grams,
                WeightUnit::Pounds,
                0.00220462262,
            ],
            // Grams - end

            // Kilograms - start
            'kilograms to grams' => [
                WeightUnit::Kilograms,
                WeightUnit::Grams,
                1000,
            ],
            'kilograms to kilograms' => [
                WeightUnit::Kilograms,
                WeightUnit::Kilograms,
                1.0,
            ],
            'kilograms to ounces' => [
                WeightUnit::Kilograms,
                WeightUnit::Ounces,
                35.2739619,
            ],
            'kilograms to pounds' => [
                WeightUnit::Kilograms,
                WeightUnit::Pounds,
                2.20462262,
            ],
            // Kilograms - end

            // Ounces - start
            'ounces to grams' => [
                WeightUnit::Ounces,
                WeightUnit::Grams,
                28.3495231,
            ],
            'ounces to kilograms' => [
                WeightUnit::Ounces,
                WeightUnit::Kilograms,
                0.0283495231,
            ],
            'ounces to ounces' => [
                WeightUnit::Ounces,
                WeightUnit::Ounces,
                1.0,
            ],
            'ounces to pounds' => [
                WeightUnit::Ounces,
                WeightUnit::Pounds,
                0.0625,
            ],
            // Ounces - end

            // Pounds - start
            'pounds to grams' => [
                WeightUnit::Pounds,
                WeightUnit::Grams,
                453.59237,
            ],
            'pounds to kilograms' => [
                WeightUnit::Pounds,
                WeightUnit::Kilograms,
                0.45359237,
            ],
            'pounds to ounces' => [
                WeightUnit::Pounds,
                WeightUnit::Ounces,
                16.0,
            ],
            'pounds to pounds' => [
                WeightUnit::Pounds,
                WeightUnit::Pounds,
                1.0,
            ],
            // Pounds - end
        ];
    }

    public static function formattedStringsProvider(): array
    {
        return [
            // Grams - start
            'kilograms and grams format when weight unit are grams' => [
                WeightUnit::Grams,
                [
                    ['value' => 0.125, 'string' => '0kg 0.125g',],
                    ['value' => 1250, 'string' => '1kg 250g',],
                    ['value' => 12.50, 'string' => '0kg 12.5g',],
                ],
            ],
            // Grams - end

            // Kilograms - start
            'kilograms and grams format when weight unit are kilograms' => [
                WeightUnit::Kilograms,
                [
                    ['value' => 0.5, 'string' => '0kg 500g',],
                    ['value' => 1250, 'string' => '1250kg 0g',],
                    ['value' => 12.50, 'string' => '12kg 500g',],
                ],
            ],
            // Kilograms - end

            // Ounces - start
            'pounds and ounces format when weight unit are ounces' => [
                WeightUnit::Ounces,
                [
                    ['value' => 0.1234, 'string' => '0lbs 0.123oz',],
                    ['value' => 1234, 'string' => '77lbs 2oz',],
                    ['value' => 12.34, 'string' => '0lbs 12.34oz',],
                ],
            ],
            // Ounces - end

            // Pounds - start
            'pounds and ounces format when weight unit are pounds' => [
                WeightUnit::Pounds,
                [
                    ['value' => 0.1234, 'string' => '0lbs 1.974oz',],
                    ['value' => 1234, 'string' => '1234lbs 0oz',],
                    ['value' => 12.24, 'string' => '12lbs 3.84oz',],
                ],
            ],
            // Pounds - end
        ];
    }


    public static function comparisionTrueResultsProvider(): array
    {
        return [
            // Grams - start
            'grams to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
            ],
            'grams to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(0.001, WeightUnit::Kilograms),
            ],
            'grams to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(0.0352739619, WeightUnit::Ounces),
            ],
            'grams to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(0.00220462262, WeightUnit::Pounds),
            ],
            // Grams - end

            // Kilograms - start
            'kilograms to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(1000, WeightUnit::Grams),
            ],
            'kilograms to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
            ],
            'kilograms to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(35.2739619, WeightUnit::Ounces),
            ],
            'kilograms to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(2.20462262, WeightUnit::Pounds),
            ],
            // Kilograms - end

            // Ounces - start
            'ounces to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(28.3495231, WeightUnit::Grams),
            ],
            'ounces to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(0.0283495231, WeightUnit::Kilograms),
            ],
            'ounces to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
            ],
            'ounces to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(0.0625, WeightUnit::Pounds),
            ],
            // Ounces - end

            // Pounds - start
            'pounds to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(453.59237, WeightUnit::Grams),
            ],
            'pounds to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(0.45359237, WeightUnit::Kilograms),
            ],
            'pounds to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(16, WeightUnit::Ounces),
            ],
            'pounds to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
            ],
            // Pounds - end
        ];
    }

    public static function comparisionFalseResultsProvider(): array
    {
        return [
            // Grams - start
            'grams to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(2.0, WeightUnit::Grams),
            ],
            'grams to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(0.002, WeightUnit::Kilograms),
            ],
            'grams to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(0.1, WeightUnit::Ounces),
            ],
            'grams to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
                Weight::fromValueAndUnit(0.1, WeightUnit::Pounds),
            ],
            // Grams - end

            // Kilograms - start
            'kilograms to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(1.0, WeightUnit::Grams),
            ],
            'kilograms to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(2.0, WeightUnit::Kilograms),
            ],
            'kilograms to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(35.0, WeightUnit::Ounces),
            ],
            'kilograms to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Kilograms),
                Weight::fromValueAndUnit(2.0, WeightUnit::Pounds),
            ],
            // Kilograms - end

            // Ounces - start
            'ounces to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(2.0, WeightUnit::Grams),
            ],
            'ounces to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(2.0, WeightUnit::Kilograms),
            ],
            'ounces to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(2.0, WeightUnit::Ounces),
            ],
            'ounces to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Ounces),
                Weight::fromValueAndUnit(2.0, WeightUnit::Pounds),
            ],
            // Ounces - end

            // Pounds - start
            'pounds to grams' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(2.0, WeightUnit::Grams),
            ],
            'pounds to kilograms' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(2.0, WeightUnit::Kilograms),
            ],
            'pounds to ounces' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(2.0, WeightUnit::Ounces),
            ],
            'pounds to pounds' => [
                Weight::fromValueAndUnit(1.0, WeightUnit::Pounds),
                Weight::fromValueAndUnit(2.0, WeightUnit::Pounds),
            ],
            // Pounds - end
        ];
    }
}
