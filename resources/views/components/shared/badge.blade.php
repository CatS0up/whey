@props([
    'type' => 'primary',
])

<span {{ $attributes->class([
    'px-2 py-1 text-xs font-semibold rounded-lg',
    'bg-green-400 text-green-100' => 'primary' === $type,
    'bg-blue-400 text-blue-100' => 'secondary' === $type,
    'bg-red-400 text-red-100' => 'danger' === $type,
    ]) }}>{{ $slot }}</span>
