@props([
'id',
'label' => null,
'invalid' => false,
'required' => false,
])

<p class="relative">
    <select id="{{ $id }}" placeholder=" " {{ $attributes->class([
        'w-full
        h-full
        p-4
        border
        border-gray-400/75
        bg-transparent
        rounded
        transition-colors
        focus:outline
        focus:outline-1
        focus:outline-teal-500
        focus:border-teal-500
        peer',
        'border-red-500 focus:outline-red-500 focus:border-red-500' => $invalid,
        ]) }}>
        {{ $slot }}
    </select>

    @if ($label)
    <label for="{{ $id }}" @class([
                'absolute
                top-1/2
                left-2
                bg-white
                text-gray-600
                font-semibold
                cursor-text
                origin-left
                whitespace-nowrap
                transition
                scale-75
                -translate-y-[175%]
                peer-focus:text-teal-500
                peer-focus:bg-white
                peer-focus:font-semibold
                peer-focus:scale-75
                ' ,
                'text-red-600 peer-focus:text-red-500'=> $invalid,
        ])>
        {{ $label . ($required ? ' *' : '')}}
    </label>
    @endif
</p>
