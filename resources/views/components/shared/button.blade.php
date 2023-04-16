@props([
    'type' => 'primary',
])

<button {{ $attributes->class([
    'block mt-4 px-4 py-2 transition-colors text-xs rounded-lg font-semibold',
    'bg-green-500 text-green-50 hover:bg-green-600 focus:bg-green-600' => 'primary' === $type,
    'bg-blue-500 text-blue-50 hover:bg-blue-600 focus:bg-blue-600' => 'secondary' === $type,
    'bg-red-500 text-red-50 hover:bg-red-600 focus:bg-red-600' => 'danger' === $type,
    ]) }} >{{ $slot }}</button>
