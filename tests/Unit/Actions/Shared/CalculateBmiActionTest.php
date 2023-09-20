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


    /** @test */
    public function it_should_calculate_bmi(): void
    {
        $weight = Weight::fromValueAndUnit(60, WeightUnit::Kilograms);
        $height = Height::fromValueAndUnit(1.60, HeightUnit::Meters);

        $actual = $this->actionUnderTest->execute($weight, $height);

        $this->assertEquals(23.4, $actual);
    }
}
