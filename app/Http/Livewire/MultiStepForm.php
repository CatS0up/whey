<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\View\View;
use InvalidArgumentException;
use Livewire\Component;

abstract class MultiStepForm extends Component
{
    /** @var int */
    public const ONE_STEP = 1;

    protected int $minStep = 1;
    protected int $maxStep = 2;

    public int $currentStep = 1;

    abstract public function render(): View;
    abstract public function submit(): mixed;

    public function nextStep(): void
    {
        if ($this->currentStep >= $this->maxStep) {
            $this->chooseStep($this->maxStep);
            return;
        }

        $this->validateStep($this->currentStep);

        $this->changeCurrentStep(self::ONE_STEP);
    }

    public function prevStep(): void
    {
        if ($this->currentStep <= $this->minStep) {
            $this->chooseStep($this->minStep);
            return;
        }

        $this->changeCurrentStep(-1 * self::ONE_STEP);
    }

    public function hasErrors(int $step): bool
    {
        if ($this->stepHasRules($step)) {
            return $this->getErrorBag()->hasAny(
                $this->getStepFieldNames($step),
            );
        }

        return false;
    }

    public function isCurrentStep(int $step): bool
    {
        return $this->currentStep === $step;
    }

    public function isInvalidStep(int $step): bool
    {
        return $this->hasErrors($step) && $this->isCurrentStep($step);
    }

    public function isVisibleSection(int $step): bool
    {
        return $this->currentStep === $step;
    }

    public function chooseStep(int $step): void
    {
        if ($step > $this->maxStep || $this->minStep > $step) {
            throw new InvalidArgumentException('The given step number is out of the available range of steps');
        }

        $this->currentStep = $step;
    }

    public function getIsFirstStepProperty(): bool
    {
        return $this->currentStep <= $this->minStep;
    }

    public function getIsLastStepProperty(): bool
    {
        return $this->currentStep >= $this->maxStep;
    }

    public function validate($rules = null, $messages = [], $attributes = []): array
    {
        return parent::validate(
            rules: $rules ?? $this->prepareRulesForFullValidation(),
            messages: $messages,
            attributes: $attributes,
        );
    }

    protected function changeCurrentStep(int $steps): void
    {
        $this->currentStep += $steps;
    }

    protected function rulesForStep(): array
    {
        return [];
    }

    protected function prepareRulesForFullValidation(): array
    {
        return array_merge(...$this->rulesForStep());
    }

    protected function validateStep(int $step): void
    {
        $rules = $this->rulesForStep();

        if ($this->stepHasRules($step)) {
            $this->validate($rules[$step]);
        }
    }

    private function stepHasRules(int $step): bool
    {
        $rules = $this->rulesForStep();

        return isset($rules[$step]);
    }

    private function getStepFieldNames(int $step): array
    {
        return array_keys(array: data_get($this->rulesForStep(), $step));
    }
}
