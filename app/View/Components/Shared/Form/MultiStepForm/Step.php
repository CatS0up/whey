<?php

declare(strict_types=1);

namespace App\View\Components\Shared\Form\MultiStepForm;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Step extends Component
{
    public function __construct(
        public string $text,
        public string $icon,
        public int $step,
        public int $currentStep,
        public bool $invalid = false,
    ) {
    }

    public function isCurrentStep(): bool
    {
        return $this->currentStep === $this->step;
    }

    public function isActiveStep(): bool
    {
        return $this->currentStep >= $this->step;
    }

    public function isDisabledStep(): bool
    {
        return ! $this->isActiveStep() || $this->isCurrentStep();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared.form.multi-step-form.step');
    }
}
