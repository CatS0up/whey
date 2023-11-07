@props([
    'title',
])

<div {{ $attributes }}>
    <p>
        <span class="font-semibold">{{ $title }}:</span>
    </p>
    <p class="text-gray-500">
        {{ $slot }}
    </p>
</div>
