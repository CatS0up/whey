@props([
    'icon',
    'target',
    'active' => false,
])
<a href="{{ $target }}" {{ $attributes->class([
    'block flex items-center p-2 rounded-lg transition-colors hover:bg-green-200/80 outline-none text-gray-500 hover:text-green-600 focus:bg-green-200/80 focus:text-green-600',
    'bg-green-200/80 text-green-600 pointer-events-none cursor-default' => $active,
]) }} >
    <span class="{{ $icon }}"></span>
    {{ $slot }}
</a>
