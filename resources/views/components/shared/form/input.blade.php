@props([
    'id',
    'label' => null,
    'type' => 'text',
    'invalid' => false,
    'required' => false,
])

<p class="relative w-full">
    <input id="{{ $id }}"
        type="{{ $type }}"
        placeholder=" "
        {{ $attributes->class([
        'w-full
        p-4
        border
        border-gray-400/75
        rounded
        transition-colors
        focus:outline
        focus:outline-1
        focus:outline-teal-500
        focus:border-teal-500
        appearance-none
        peer',
        'border-red-500 focus:outline-red-500 focus:border-red-500' => $invalid,
        ]) }}>

        <label for="{{ $id }}"
            @class([
                'absolute
                top-1/2
                left-4
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
                peer-focus:-translate-y-[175%]
                peer-placeholder-shown:bg-transparent
                peer-placeholder-shown:scale-100
                peer-placeholder-shown:-translate-y-1/2
                peer-placeholder-shown:font-normal
                ',
                'text-red-600 peer-focus:text-red-500' => $invalid,
            ])>
                {{ $label . ($required ? ' *' : '')}}
        </label>
</p>
