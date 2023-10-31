@props([
    'label',
])

<div {{ $attributes
    ->class([
        'px-2
        py-1
        border
        border-gray-900
        text-center
        rounded-lg
        text-sm
        font-semibold
        transition-colors
        cursor-pointer
        hover:bg-teal-500
        hover:text-teal-50
        hover:border-teal-500
        focus:bg-teal-500
        focus:text-teal-50
        focus:border-teal-500
        outline-none'])
    ->merge(['tabindex' => 0]) }}>
    <span>
        {{ $label }}
    </span>
</div>
