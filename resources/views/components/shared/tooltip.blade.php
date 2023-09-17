@push('styles')
    @once
        @vite(['node_modules/tippy.js/dist/tippy.css'])
    @endonce
@endpush

@props([
    'text',
    'position' => 'top',
])

<div x-data x-init="$nextTick(() => {
    const tooltip = $refs.tooltip;
    const child = tooltip.firstElementChild;
    const instance = tippy(
        tooltip,
        {
            content: @js($text),
            placement: @js($position),
        }
    )

    child.addEventListener('focus', () => instance.show());
    child.addEventListener('blur', () => instance.hide());
    })"
    x-ref="tooltip">
    {{ $slot }}
</div>

@push('scripts')
    @once
        @vite([
            'node_modules/@popperjs/core/dist/umd/popper.js',
            'node_modules/tippy.js/dist/tippy.umd.js',
            ])
    @endonce
@endpush
