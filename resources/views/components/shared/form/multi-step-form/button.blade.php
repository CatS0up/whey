<x-shared.button
    {{ $attributes->merge([
        'wire:target' => 'chooseStep,prevStep,nextStep,submit',
        'wire:loading.attr' => 'disabled',
        'wire:loading.class' => 'pointer-events-none',
    ]) }}>
    {{ $slot }}
</x-shared.button>
