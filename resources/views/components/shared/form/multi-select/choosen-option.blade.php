@props([
'label',
])

<div {{ $attributes
    ->class([
        'group
        relative
        px-2
        py-1
        text-center
        rounded-lg
        text-sm
        bg-teal-500
        text-teal-50
        font-semibold
        overflow-hidden
        cursor-pointer
        outline-none'])
    ->merge(['tabindex' => 0]) }}>
    <span>
        {{ $label }}
    </span>

    <div class="absolute hidden inset-0 flex-row items-center justify-center bg-black/75 text-xs group-hover:flex group-focus:flex">
        <span>
            Usuń
        </span>
        <span class="fa-regular fa-trash-can ml-2"></span>
    </div>
</div>
