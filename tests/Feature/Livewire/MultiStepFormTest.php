<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use InvalidArgumentException;
use Livewire\Livewire;
use Tests\Mocks\Livewire\MultiStepFormMock;
use Tests\TestCase;

class MultiStepFormTest extends TestCase
{
    private const CLASS_UNDER_TEST = MultiStepFormMock::class;

    /** @test */
    public function it_should_throw_InvalidArgumentException_when_given_step_is_out_of_steps_range(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The given step number is out of the available range of steps');

        Livewire::test(self::CLASS_UNDER_TEST)
            ->call('chooseStep', 0);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The given step number is out of the available range of steps');

        Livewire::test(self::CLASS_UNDER_TEST)
            ->call('chooseStep', 4);
    }

    /** @test */
    public function it_should_set_max_value_as_current_step_property_value_when_current_step_is_greater_than_max_value(): void
    {
        Livewire::test(self::CLASS_UNDER_TEST)
            ->set('currentStep', 1)
            ->assertSee('Current step 1')
            ->call('nextStep')
            ->assertSee('Current step 2')
            ->call('nextStep')
            ->assertSee('Current step 3')
            ->call('nextStep') // 4
            ->assertSee('Current step 3')
            ->call('nextStep') // 4
            ->assertSee('Current step 3');
    }

    /** @test */
    public function it_should_set_min_value_as_current_step_property_value_when_current_step_is_less_than_min_value(): void
    {
        Livewire::test(self::CLASS_UNDER_TEST)
            ->set('currentStep', 2)
            ->assertSee('Current step 2')
            ->call('prevStep')
            ->assertSee('Current step 1')
            ->call('prevStep') // 0
            ->assertSee('Current step 1')
            ->call('prevStep') // 0
            ->assertSee('Current step 1');
    }

    /** @test */
    public function it_should_validate_multistep_form_step_by_step(): void
    {
        Livewire::test(self::CLASS_UNDER_TEST)
            // Step 1 - start
            ->set('first_step_property', '')
            ->set('second_step_property', '')
            ->set('third_step_property', '')
            ->call('nextStep')
            ->assertHasErrors('first_step_property')
            ->assertHasNoErrors('second_step_property')
            ->assertHasNoErrors('third_step_property')
            ->set('first_step_property', 'Filled')
            // Step 1 - end

            // Step 2 - start
            ->call('nextStep')
            ->assertHasNoErrors('first_step_property')
            ->assertHasNoErrors('second_step_property')
            ->assertHasNoErrors('third_step_property')
            ->set('second_step_property', 'Filled')
            // Step 2 - end

            // Step 3 - start
            ->call('nextStep')
            ->assertHasNoErrors('first_step_property')
            ->assertHasNoErrors('second_step_property')
            ->assertHasNoErrors('third_step_property');
        // Step end - start
    }

    /** @test */
    public function it_should_validate_all_properties_multistep_when_validate_method_was_called(): void
    {
        Livewire::test(self::CLASS_UNDER_TEST)
            ->set('first_step_property', '')
            ->set('second_step_property', '')
            ->set('third_step_property', '')
            ->call('validate')
            ->assertHasErrors('first_step_property')
            ->assertHasErrors('second_step_property')
            ->assertHasErrors('third_step_property');
    }
}
