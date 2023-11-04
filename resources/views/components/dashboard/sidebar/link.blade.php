@props([
    'icon',
    'target',
    'active' => false,
])
    <a href="{{ $target }}" {{ $attributes->class([
        'block
        flex
        items-center
        p-2
        rounded-lg
        transition-colors
        outline-none
        text-gray-500/80
        hover:bg-gray-100/80
        focus:bg-gray-100/80',
        'bg-gray-100/80 pointer-events-none cursor-default' => $active,
    ]) }} >
    <span class="{{ $icon }}"></span>
    {{ $slot }}
</a>
