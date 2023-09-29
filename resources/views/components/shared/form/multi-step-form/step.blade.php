<x-shared.tooltip :text="$text">
    <x-shared.stepper.step
        {{ $attributes->merge([
            'wire:target' => 'chooseStep,prevStep,nextStep,submit',
            'wire:loading.attr' => 'disabled',
            'wire:loading.class' => 'pointer-events-none',
        ]) }}
        wire:click="chooseStep({{ $step }})"
        :icon="$icon"
        :step="$step"
        :active="$isActiveStep()"
        :disabled="$isDisabledStep()"
        :invalid="$invalid"
    />
</x-shared.tooltip>
