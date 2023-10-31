@props([
    'invalid' => false,
])

<textarea {{ $attributes->class([
    'px-2 py-1 border rounded focus:outline-0',
    'border-red-400' => $invalid,
    ]) }}>
</textarea>
