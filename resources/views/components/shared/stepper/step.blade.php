@props([
    'icon',
    'step',
    'active' => false,
    'disabled' => false,
    'invalid' => false,
])

<button x-data="{
        handle(e) {
            const targetClasses = e.target.classList;

            {{-- We are watching the 'pointer-events-none presence' class because only disabled steps contain it --}}
            if (targetClasses.contains('pointer-events-none') && 'Tab' !== e.key) {
                e.preventDefault();
                }
            }
        }"
        @keydown="handle"
    {{ $attributes->class([
        'w-7 h-7 bg-gray-300 text-gray-50 rounded-full outline-none',
        'bg-teal-500 text-teal-50' => $active && !$invalid,
        'pointer-events-none cursor-auto' => $disabled,
        'bg-red-500 text-red-50' => $invalid,
    ]) }}>
    <span class="{{ $icon }}"></span>
</button>
