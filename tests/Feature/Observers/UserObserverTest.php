<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Actions\Shared\CalculateBmiAction;
use App\Enums\HeightUnit;
use App\Enums\WeightUnit;
use App\Models\User;
use App\ValueObjects\Shared\Height;
use App\ValueObjects\Shared\Weight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\Mock;
use Tests\Concerns\Authorization;
use Tests\TestCase;

class UserObserverTest extends TestCase
{
    use Authorization;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->runPermissionSeeders();

        Mockery::globalHelpers();
    }

    /** @test */
    public function it_should_assign_the_user_role_as_the_default_role_for_newly_created_user(): void
    {
        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $this->assertTrue($user->roles->isNotEmpty());
            $this->assertCount(1, $user->roles);
            $this->assertEquals('user', $user->roles->first()->name);
        }
    }

    /**
     * @dataProvider \Tests\Unit\Actions\Shared\CalculateBmiActionTest::bmiForDifferentWeightAndHeightUnitsProvider
     *
     * @test
     * */
    public function it_should_calculate_the_bmi_for_every_newly_created_user_for_different_height_and_weight_units(
        HeightUnit $heightUnit,
        WeightUnit $weightUnit,
        float $heightValue,
        float $weightValue,
        float $expectedBmi,
    ): void {
        $height = Height::fromValueAndUnit($heightValue, $heightUnit);
        $weight = Weight::fromValueAndUnit($weightValue, $weightUnit);

        /** @var Mock|CalculateBmiAction $calculateBmiActionMock */
        $calculateBmiActionMock = spy(CalculateBmiAction::class)
            ->makePartial();
        $calculateBmiActionMock->execute($weight, $height);

        app()->instance(CalculateBmiAction::class, $calculateBmiActionMock);

        $user = User::factory()->create(compact('weight', 'height'));

        $this->assertEquals($expectedBmi, $user->bmi);
    }

    /** @test */
    public function it_should_recalculate_the_bmi_for_every_user_that_has_been_updated_specifically_when_the_height_property_has_changed(
    ): void {
        $initialHeight = Height::fromValueAndUnit(1.75, HeightUnit::Meters);
        $initialWeight = Weight::fromValueAndUnit(70, WeightUnit::Kilograms);
        $user = User::factory()->create([
            'height' => $initialHeight,
            'weight' => $initialWeight,
        ]);

        $this->assertTrue($user->height->equalsTo($initialHeight));
        $this->assertTrue($user->weight->equalsTo($initialWeight));
        $this->assertEquals(22.86, $user->bmi);

        $newHeight = Height::fromValueAndUnit(1.80, HeightUnit::Meters);
        /** @var Mock|CalculateBmiAction $calculateBmiActionMock */
        $calculateBmiActionMock = spy(CalculateBmiAction::class)
            ->makePartial();
        $calculateBmiActionMock
            ->expects('execute')
            ->withArgs(function (Weight $weight, Height $height) use ($initialWeight, $newHeight) {
                $this->assertTrue($weight->equalsTo($initialWeight));
                $this->assertTrue($height->equalsTo($newHeight));

                return true;
            })
            ->andReturn(21.6);

        app()->instance(CalculateBmiAction::class, $calculateBmiActionMock);

        $user->update([
            'height' => $newHeight,
        ]);

        $this->assertTrue($user->height->equalsTo($newHeight));
        $this->assertTrue($user->weight->equalsTo($initialWeight));
        $this->assertEquals(21.6, $user->bmi);
    }

    public function it_should_recalculate_the_bmi_for_every_user_that_has_been_updated_specifically_when_the_weight_property_has_changed(): void
    {
        $initialHeight = Height::fromValueAndUnit(1.75, HeightUnit::Meters);
        $initialWeight = Weight::fromValueAndUnit(70, WeightUnit::Kilograms);
        $user = User::factory()->create([
            'height' => $initialHeight,
            'weight' => $initialWeight,
        ]);

        $this->assertTrue($user->height->equalsTo($initialHeight));
        $this->assertTrue($user->weight->equalsTo($initialWeight));
        $this->assertEquals(22.86, $user->bmi);

        $newWeight = Weight::fromValueAndUnit(80, WeightUnit::Kilograms);
        /** @var Mock|CalculateBmiAction $calculateBmiActionMock */
        $calculateBmiActionMock = spy(CalculateBmiAction::class)
            ->makePartial();
        $calculateBmiActionMock
            ->expects('execute')
            ->withArgs(function (Weight $weight, Height $height) use ($newWeight, $initialHeight) {
                $this->assertTrue($weight->equalsTo($newWeight));
                $this->assertTrue($height->equalsTo($initialHeight));

                return true;
            })
            ->andReturn(26.12);

        app()->instance(CalculateBmiAction::class, $calculateBmiActionMock);

        $user->update([
            'weight' => $newWeight,
        ]);

        $this->assertTrue($user->height->equalsTo($newWeight));
        $this->assertTrue($user->weight->equalsTo($initialWeight));
        $this->assertEquals(26.12, $user->bmi);
    }
}
