@props([
    'icon',
])

<div {{ $attributes->class(['flex items-center justify-center w-10 h-10 mx-auto rounded-full bg-gray-400/80']) }}>
    <span class="{{ $icon . ' text-gray-100' }}"></span>
</div>
