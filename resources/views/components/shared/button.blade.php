@props([
    'variant' => 'primary',
    'type' => 'button',
])

<button type="{{ $type }}" {{ $attributes->class([
    'block min-w-fit p-3.5 text-sm transition-colors rounded font-semibold uppercase outline-none drop-shadow-md',
    'bg-teal-500 text-teal-50 hover:bg-teal-600 focus:bg-teal-600 group-focus:bg-teal-600 disabled:bg-teal-600' => 'primary' === $variant,
    'bg-emerald-500 text-emerald-50 hover:bg-emerald-600 focus:bg-emerald-600 group-focus:bg-emerald-600 disabled:bg-emerald-600' => 'secondary' === $variant,
    'bg-red-500 text-red-50 hover:bg-red-600 focus:bg-red-600 group-focus:bg-red-600 disabled:bg-red-600' => 'danger' === $variant,
    'bg-transparent text-gray-900 hover:bg-teal-50 focus:bg-teal-50 group-focus:bg-teal-50 border-solid border border-gray-900' => 'transparent' === $variant,
    ]) }} >{{ $slot }}</button>
