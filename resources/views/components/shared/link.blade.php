@props([
    'href' => '#',
])

<a href="{{ $href }}" {{ $attributes->class(['text-blue-700 outline-none transition-colors active:text-red-500 focus:text-red-500']) }}>
    {{ $slot }}
</a>
