<?php

declare(strict_types=1);

namespace Tests\Mocks\Livewire;

use App\Http\Livewire\MultiStepForm;
use Illuminate\View\View;

class MultiStepFormMock extends MultiStepForm
{
    protected int $minStep = 1;
    protected int $maxStep = 3;

    public string $first_step_property = 'step 1';
    public string $second_step_property = 'step 2';
    public string $third_step_property = 'step 3';

    public function submit(): mixed
    {
        return true;
    }

    public function render(): View
    {
        return view('multi-step-form-mock');
    }

    protected function rulesForStep(): array
    {
        return [
            1 => [
                'first_step_property' => [
                    'required',
                ],
            ],
            2 => [
                'second_step_property' => [
                    'required',
                ],
            ],
            3 => [
                'third_step_property' => [
                    'required',
                ],
            ],
        ];
    }
}
